import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";
import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function Profile() {
	const [user, setUser] = useState(null);
	const [addresses, setAddresses] = useState([]);
	const [orders, setOrders] = useState([]);
	const [newAddress, setNewAddress] = useState(null);
	const [editingAddress, setEditingAddress] = useState(null);
	const [formErrors, setFormErrors] = useState({});
	const [successMsg, setSuccessMsg] = useState({});
	const navigate = useNavigate();
	const [activeTab, setActiveTab] = useState(
		window.location.search.split("?")[1] || "profile"
	);

	// Pagination state for orders
	const [currentPage, setCurrentPage] = useState(1);
	const [ordersPerPage] = useState(5); // Show 5 orders per page

	const fetchAddresses = async (id) => {
		try {
			const response = await fetch(`${API_URL}customerAddresses/${id}`, {
				method: "GET",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			});
			if (!response.ok) {
				throw new Error("Failed to fetch addresses");
			}
			const data = await response.json();
			setAddresses(data.addresses);
			// console.log(data.addresses);
		} catch (error) {
			console.error(error);
		}
	};

	const fetchMyOrders = async (id) => {
		try {
			const response = await fetch(
				`${API_URL}my-orders?customer_id=${id}`,
				{
					method: "GET",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"token"
						)}`,
					},
				}
			);
			if (!response.ok) {
				throw new Error("Failed to fetch orders");
			}
			const data = await response.json();
			setOrders(data.orders);
		} catch (error) {
			console.error(error);
		}
	};

	useEffect(() => {
		const storedUser = localStorage.getItem("user");
		if (storedUser) {
			setActiveTab(window.location.search.split("?")[1] || "profile");
			setUser(JSON.parse(storedUser));
			fetchAddresses(JSON.parse(storedUser).id);
			fetchMyOrders(JSON.parse(storedUser).id);
		} else {
			navigate("/login");
		}
	}, []);

	if (!user) {
		return <div>Loading...</div>;
	}

	const updateCustomerInfo = async (e) => {
		e.preventDefault();
		try {
			const response = await fetch(`${API_URL}customers/${user.id}`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				body: JSON.stringify(user),
			});
			if (!response.ok) {
				setFormErrors({ submit: "Failed to update your information" });
				throw new Error("Failed to update your information");
			}
			const updatedUser = await response.json();
			setUser(updatedUser.customer);
			setFormErrors({});
			setSuccessMsg({ submit: "Your information updated successfully" });
			localStorage.setItem("user", JSON.stringify(updatedUser.customer));
		} catch (error) {
			setUser(user);
			setFormErrors({ submit: error.message });
			console.error(error);
		}
	};

	const saveNewAddress = async (e) => {
		e.preventDefault();
		console.log(JSON.stringify(newAddress));
		try {
			const response = await fetch(
				`${API_URL}customerNewAddress/${user.id}`,
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"token"
						)}`,
					},
					body: JSON.stringify(newAddress),
				}
			);
			console.log(response);
			if (!response.ok) {
				setFormErrors({ submit: "Failed to add new address" });
				throw new Error("Failed to add new address");
			}
			const addedAddress = await response.json();
			setFormErrors({});
			setSuccessMsg({ submit: "New address added successfully" });

			const modal = document.getElementById("addAddressModal");
			if (modal) {
				modal.classList.remove("show");
				modal.setAttribute("aria-hidden", "true");
				const backdrop = document.querySelector(".modal-backdrop");
				if (backdrop) {
					backdrop.remove();
				}
			}
			setNewAddress(null);
		} catch (error) {
			setFormErrors({ submit: error.message });
			console.error(error);
		}
	};

	// Edit address handler
	const handleEditAddress = (address) => {
		setEditingAddress({
			id: address.id,
			addressLabel: address.label,
			fullName: address.full_name,
			phoneNumber: address.phone_number,
			addressLine1: address.address_line1,
			addressLine2: address.address_line2,
			city: address.city,
			state: address.state,
			zipCode: address.postal_code,
			type: address.type,
			isDefault: address.is_default === 1,
		});
		setFormErrors({});
		setSuccessMsg({});
	};

	// Update address handler
	const updateAddress = async (e) => {
		e.preventDefault();
		try {
			const response = await fetch(
				`${API_URL}customerUpdateAddress/${editingAddress.id}`,
				{
					method: "PUT",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"token"
						)}`,
					},
					body: JSON.stringify(editingAddress),
				}
			);
			if (!response.ok) {
				setFormErrors({ submit: "Failed to update address" });
				throw new Error("Failed to update address");
			}
			const updatedData = await response.json();
			setFormErrors({});
			setSuccessMsg({ submit: "Address updated successfully" });

			// Close modal
			const modal = document.getElementById("editAddressModal");
			if (modal) {
				modal.classList.remove("show");
				modal.setAttribute("aria-hidden", "true");
				const backdrop = document.querySelector(".modal-backdrop");
				if (backdrop) {
					backdrop.remove();
				}
			}

			setEditingAddress(null);
			// Refresh addresses list
			fetchAddresses(user.id);
		} catch (error) {
			setFormErrors({ submit: error.message });
			console.error(error);
		}
	};

	// Delete address handler
	const handleDeleteAddress = async (addressId) => {
		const modalId = `deleteModal${addressId}`;
		const modalElement = document.getElementById(modalId);
		const modal = new window.bootstrap.Modal(modalElement);
		modal.show();

		// Wait for user confirmation
		const confirmBtn = modalElement.querySelector(".btn-confirm-delete");
		const cancelBtn = modalElement.querySelector(".btn-cancel-delete");

		const result = await new Promise((resolve) => {
			const handleConfirm = () => {
				cleanup();
				resolve({ isConfirmed: true });
			};

			const handleCancel = () => {
				cleanup();
				resolve({ isConfirmed: false });
			};

			const cleanup = () => {
				confirmBtn.removeEventListener("click", handleConfirm);
				cancelBtn.removeEventListener("click", handleCancel);
				modal.hide();
			};

			confirmBtn.addEventListener("click", handleConfirm);
			cancelBtn.addEventListener("click", handleCancel);
		});

		if (!result.isConfirmed) {
			return;
		}

		try {
			const response = await fetch(
				`${API_URL}customerDeleteAddress/${addressId}`,
				{
					method: "DELETE",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"token"
						)}`,
					},
				}
			);
			if (!response.ok) {
				throw new Error("Failed to delete address");
			}
			setSuccessMsg({ submit: "Address deleted successfully" });
			// Refresh addresses list
			fetchAddresses(user.id);
		} catch (error) {
			setFormErrors({ submit: error.message });
			console.error(error);
		}
	};
	// Make address default handler
	const handleMakeDefault = async (addressId) => {
		try {
			const response = await fetch(
				`${API_URL}customerSetDefaultAddress/${addressId}`,
				{
					method: "PUT",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"token"
						)}`,
					},
				}
			);
			if (!response.ok) {
				throw new Error("Failed to set default address");
			}
			setSuccessMsg({ submit: "Default address updated successfully" });
			// Refresh addresses list
			fetchAddresses(user.id);
		} catch (error) {
			setFormErrors({ submit: error.message });
			console.error(error);
		}
	};

	// Pagination logic for orders
	const indexOfLastOrder = currentPage * ordersPerPage;
	const indexOfFirstOrder = indexOfLastOrder - ordersPerPage;
	const currentOrders = orders.slice(indexOfFirstOrder, indexOfLastOrder);
	const totalPages = Math.ceil(orders.length / ordersPerPage);

	// Change page
	const paginate = (pageNumber) => {
		setCurrentPage(pageNumber);
		// Scroll to top of orders section
		const ordersSection = document.querySelector(".orders-table");
		if (ordersSection) {
			ordersSection.scrollIntoView({
				behavior: "smooth",
				block: "start",
			});
		}
	};

	// Generate page numbers array
	const getPageNumbers = () => {
		const pageNumbers = [];
		const maxVisiblePages = 5;

		if (totalPages <= maxVisiblePages) {
			// Show all pages if total is less than max visible
			for (let i = 1; i <= totalPages; i++) {
				pageNumbers.push(i);
			}
		} else {
			// Show smart pagination with ellipsis
			if (currentPage <= 3) {
				// Show first 5 pages
				for (let i = 1; i <= 5; i++) {
					pageNumbers.push(i);
				}
				pageNumbers.push("...");
				pageNumbers.push(totalPages);
			} else if (currentPage >= totalPages - 2) {
				// Show last 5 pages
				pageNumbers.push(1);
				pageNumbers.push("...");
				for (let i = totalPages - 4; i <= totalPages; i++) {
					pageNumbers.push(i);
				}
			} else {
				// Show pages around current page
				pageNumbers.push(1);
				pageNumbers.push("...");
				for (let i = currentPage - 1; i <= currentPage + 1; i++) {
					pageNumbers.push(i);
				}
				pageNumbers.push("...");
				pageNumbers.push(totalPages);
			}
		}

		return pageNumbers;
	};

	return (
		<section id="account" className="account section">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
				{/* Mobile Sidebar Toggle Button */}
				<div className="sidebar-toggle d-lg-none mb-3">
					<button
						className="btn btn-toggle"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#profileSidebar"
						aria-expanded="false"
						aria-controls="profileSidebar"
					>
						<i className="bi bi-list me-2"></i> Profile Menu
					</button>
				</div>

				<div className="row">
					{/* Profile Sidebar */}
					<div
						className="col-lg-3 profile-sidebar collapse d-lg-block aos-init aos-animate"
						id="profileSidebar"
						data-aos="fade-right"
						data-aos-delay="200"
					>
						<div className="profile-header">
							<div className="profile-avatar">
								<span>
									{typeof user?.name === "string" &&
									user.name.charAt(0) ? (
										user.name.charAt(0)
									) : (
										<i className="bi bi-person-circle"></i>
									)}
								</span>
							</div>
							<div className="profile-info">
								<h4>{user?.name}</h4>
								<div className="profile-bonus d-none">
									<i className="bi bi-gift"></i>
									<span>100 bonuses available</span>
								</div>
							</div>
						</div>

						<div className="profile-nav">
							<ul
								className="nav flex-column"
								id="profileTabs"
								role="tablist"
							>
								<li className="nav-item" role="presentation">
									<button
										className={
											activeTab === "profile"
												? "nav-link active"
												: "nav-link"
										}
										id="personal-tab"
										data-bs-toggle="tab"
										data-bs-target="#personal"
										type="button"
										role="tab"
										aria-controls="personal"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-person"></i>
										<span>Personal info</span>
									</button>
								</li>
								<li className="nav-item" role="presentation">
									<button
										className={
											activeTab === "orders"
												? "nav-link active"
												: "nav-link"
										}
										id="orders-tab"
										data-bs-toggle="tab"
										data-bs-target="#orders"
										type="button"
										role="tab"
										aria-controls="orders"
										aria-selected="true"
									>
										<i className="bi bi-box-seam"></i>
										<span>Orders</span>
										<span className="badge">
											{orders.length ? orders.length : 0}
										</span>
									</button>
								</li>
								<li
									className="nav-item d-none"
									role="presentation"
								>
									<button
										className={
											activeTab === "wishlist"
												? "nav-link active"
												: "nav-link"
										}
										id="wishlist-tab"
										data-bs-toggle="tab"
										data-bs-target="#wishlist"
										type="button"
										role="tab"
										aria-controls="wishlist"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-heart"></i>
										<span>Wishlist</span>
									</button>
								</li>
								<li
									className="nav-item d-none"
									role="presentation"
								>
									<button
										className={
											activeTab === "payment"
												? "nav-link active"
												: "nav-link"
										}
										id="payment-tab"
										data-bs-toggle="tab"
										data-bs-target="#payment"
										type="button"
										role="tab"
										aria-controls="payment"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-credit-card"></i>
										<span>Payment methods</span>
									</button>
								</li>
								<li
									className="nav-item d-none"
									role="presentation"
								>
									<button
										className={
											activeTab === "reviews"
												? "nav-link active"
												: "nav-link"
										}
										id="reviews-tab"
										data-bs-toggle="tab"
										data-bs-target="#reviews"
										type="button"
										role="tab"
										aria-controls="reviews"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-star"></i>
										<span>My reviews</span>
									</button>
								</li>
								<li className="nav-item" role="presentation">
									<button
										className={
											activeTab === "addresses"
												? "nav-link active"
												: "nav-link"
										}
										id="addresses-tab"
										data-bs-toggle="tab"
										data-bs-target="#addresses"
										type="button"
										role="tab"
										aria-controls="addresses"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-geo-alt"></i>
										<span>Addresses</span>
									</button>
								</li>
								<li
									className="nav-item d-none"
									role="presentation"
								>
									<button
										className={
											activeTab === "notifications"
												? "nav-link active"
												: "nav-link"
										}
										id="notifications-tab"
										data-bs-toggle="tab"
										data-bs-target="#notifications"
										type="button"
										role="tab"
										aria-controls="notifications"
										aria-selected="false"
										tabIndex="-1"
									>
										<i className="bi bi-bell"></i>
										<span>Notifications</span>
									</button>
								</li>
							</ul>

							<h6 className="nav-section-title">
								Customer service
							</h6>
							<ul className="nav flex-column">
								<li className="nav-item">
									<a href="#" className="nav-link">
										<i className="bi bi-question-circle"></i>
										<span>Help center</span>
									</a>
								</li>
								<li className="nav-item">
									<a href="#" className="nav-link">
										<i className="bi bi-file-text"></i>
										<span>Terms and conditions</span>
									</a>
								</li>
								<li className="nav-item">
									<a
										href="/logout"
										className="nav-link logout"
									>
										<i className="bi bi-box-arrow-right"></i>
										<span>Log out</span>
									</a>
								</li>
							</ul>
						</div>
					</div>

					{/* Profile Content */}
					<div
						className="col-lg-9 profile-content aos-init aos-animate"
						data-aos="fade-left"
						data-aos-delay="300"
					>
						<div className="tab-content" id="profileTabsContent">
							{/* Personal Info Tab */}
							<div
								className={
									activeTab === "profile"
										? "tab-pane fade active show"
										: "tab-pane fade"
								}
								id="personal"
								role="tabpanel"
								aria-labelledby="personal-tab"
							>
								<div className="tab-header">
									<h2>Personal Information</h2>
								</div>
								<div
									className="personal-info-form aos-init aos-animate"
									data-aos="fade-up"
									data-aos-delay="100"
								>
									<form
										className="php-email-form"
										onSubmit={updateCustomerInfo}
									>
										{formErrors.submit && (
											<div className="alert alert-danger">
												{formErrors.submit}
											</div>
										)}
										{successMsg.submit && (
											<div className="alert alert-success">
												{successMsg.submit}
											</div>
										)}
										<div className="row">
											<div className="col-md-8 mb-3">
												<label
													htmlFor="name"
													className="form-label"
												>
													Name
												</label>
												<input
													type="text"
													className="form-control"
													id="name"
													name="name"
													value={user.name}
													onChange={(e) =>
														setUser({
															...user,
															name: e.target
																.value,
														})
													}
													required=""
												/>
											</div>
											<div className="col-md-4 mb-3">
												<label
													htmlFor="birthday"
													className="form-label"
												>
													Date of Birth
												</label>
												<input
													type="date"
													className="form-control"
													id="birthday"
													name="birthday"
													value={user.birthday}
													onChange={(e) =>
														setUser({
															...user,
															birthday:
																e.target.value,
														})
													}
												/>
											</div>
										</div>
										<div className="row">
											<div className="col-md-6 mb-3">
												<label
													htmlFor="email"
													className="form-label"
												>
													Email
												</label>
												<input
													type="email"
													className="form-control"
													id="email"
													name="email"
													readOnly
													value={user.email}
													onChange={(e) =>
														setUser({
															...user,
															email: e.target
																.value,
														})
													}
													required=""
												/>
											</div>
											<div className="col-md-6 mb-3">
												<label
													htmlFor="phone"
													className="form-label"
												>
													Phone
												</label>
												<input
													type="tel"
													className="form-control"
													id="phone"
													name="phone"
													value={user.phone}
													onChange={(e) =>
														setUser({
															...user,
															phone: e.target
																.value,
														})
													}
												/>
											</div>
										</div>
										<div className="mb-3">
											<label className="form-label d-block">
												Gender
											</label>
											<div className="form-check form-check-inline">
												<input
													className="form-check-input"
													type="radio"
													name="gender"
													id="genderMale"
													value="male"
													checked={
														user?.gender === "male"
													}
													onChange={(e) =>
														setUser({
															...user,
															gender: e.target
																.value,
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="genderMale"
												>
													Male
												</label>
											</div>
											<div className="form-check form-check-inline">
												<input
													className="form-check-input"
													type="radio"
													name="gender"
													id="genderFemale"
													value="female"
													checked={
														user?.gender ===
														"female"
													}
													onChange={(e) =>
														setUser({
															...user,
															gender: e.target
																.value,
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="genderFemale"
												>
													Female
												</label>
											</div>
										</div>
										<div className="loading">Loading</div>
										<div className="error-message"></div>
										<div className="sent-message">
											Your information has been updated.
											Thank you!
										</div>
										<div className="text-end">
											<button
												type="submit"
												className="btn btn-save"
											>
												Save Changes
											</button>
										</div>
									</form>
								</div>
							</div>

							{/* Orders Tab */}
							<div
								className={
									activeTab === "orders"
										? "tab-pane fade active show"
										: "tab-pane fade"
								}
								id="orders"
								role="tabpanel"
								aria-labelledby="orders-tab"
							>
								<div className="tab-header">
									<h2>Orders</h2>
									<div className="tab-filters">
										<div className="row">
											<div className="col-md-6 mb-3 mb-md-0">
												<div className="dropdown">
													<button
														className="btn dropdown-toggle"
														type="button"
														id="statusFilter"
														data-bs-toggle="dropdown"
														aria-expanded="false"
													>
														<span>
															Select status
														</span>
														<i className="bi bi-chevron-down"></i>
													</button>
													<ul
														className="dropdown-menu"
														aria-labelledby="statusFilter"
													>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																All statuses
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																In progress
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																Delivered
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																Canceled
															</a>
														</li>
													</ul>
												</div>
											</div>
											<div className="col-md-6">
												<div className="dropdown">
													<button
														className="btn dropdown-toggle"
														type="button"
														id="timeFilter"
														data-bs-toggle="dropdown"
														aria-expanded="false"
													>
														<span>
															For all time
														</span>
														<i className="bi bi-chevron-down"></i>
													</button>
													<ul
														className="dropdown-menu"
														aria-labelledby="timeFilter"
													>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																For all time
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																Last 30 days
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																Last 6 months
															</a>
														</li>
														<li>
															<a
																className="dropdown-item"
																href="#"
															>
																Last year
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>{" "}
								<div className="orders-table table-responsive aos-init aos-animate">
									<table className="table table-bordered table-hover">
										<thead>
											<tr className="text-center">
												<th>Order ID</th>
												<th>Order date</th>
												<th>Status</th>
												<th>Payment Details</th>
												<th>Total</th>
												<th>View Order</th>
											</tr>
										</thead>
										<tbody>
											{currentOrders &&
											currentOrders.length > 0 ? (
												currentOrders.map(
													(order, index) => (
														<tr
															key={
																order.id ||
																index
															}
														>
															<td className="text-center">
																{order.id}
															</td>
															<td className="text-center">
																{
																	order.order_date
																}
															</td>
															<td className="text-center">
																<div
																	className={`order-status ${order.status
																		.toLowerCase()
																		.replace(
																			" ",
																			"-"
																		)}`}
																>
																	<span className="status-dot"></span>
																	<span className="text-capitalize">
																		{
																			order.status
																		}
																	</span>
																</div>
															</td>
															<td className="text-center">
																{
																	order.payment_method
																}
															</td>
															<td className="text-center">
																{formatCurrency(
																	order.final_amount
																)}
															</td>
															<td className="text-center p-1">
																<a
																	href={`/order-details?order_id=${order.id}`}
																	className="btn btn-view-order"
																>
																	<i
																		className="bi bi-eye"
																		style={{
																			marginRight:
																				"5px",
																		}}
																	></i>
																	View
																</a>
															</td>
														</tr>
													)
												)
											) : (
												<tr>
													<td
														colSpan="6"
														className="text-center"
													>
														{orders.length === 0
															? "No orders found."
															: "No orders on this page."}
													</td>
												</tr>
											)}
										</tbody>
									</table>

									{/* Dynamic Pagination */}
									{orders.length > ordersPerPage && (
										<div>
											<div className="pagination-container pt-2 pb-0">
												<nav aria-label="Orders pagination">
													<ul className="pagination">
														{/* Previous Button */}
														<li
															className={`page-item ${
																currentPage ===
																1
																	? "disabled"
																	: ""
															}`}
														>
															<button
																className="page-link"
																onClick={() =>
																	paginate(
																		currentPage -
																			1
																	)
																}
																disabled={
																	currentPage ===
																	1
																}
																aria-label="Previous"
															>
																<i className="bi bi-chevron-left"></i>
															</button>
														</li>

														{/* Page Numbers */}
														{getPageNumbers().map(
															(page, index) =>
																page ===
																"..." ? (
																	<li
																		key={`ellipsis-${index}`}
																		className="page-item disabled"
																	>
																		<span className="page-link">
																			...
																		</span>
																	</li>
																) : (
																	<li
																		key={
																			page
																		}
																		className={`page-item ${
																			currentPage ===
																			page
																				? "active"
																				: ""
																		}`}
																	>
																		<button
																			className="page-link"
																			onClick={() =>
																				paginate(
																					page
																				)
																			}
																		>
																			{
																				page
																			}
																		</button>
																	</li>
																)
														)}

														{/* Next Button */}
														<li
															className={`page-item ${
																currentPage ===
																totalPages
																	? "disabled"
																	: ""
															}`}
														>
															<button
																className="page-link"
																onClick={() =>
																	paginate(
																		currentPage +
																			1
																	)
																}
																disabled={
																	currentPage ===
																	totalPages
																}
																aria-label="Next"
															>
																<i className="bi bi-chevron-right"></i>
															</button>
														</li>
													</ul>
												</nav>
											</div>

											{/* Pagination Info */}
											<div className="pagination-info pb-3">
												Showing {indexOfFirstOrder + 1}{" "}
												to{" "}
												{Math.min(
													indexOfLastOrder,
													orders.length
												)}{" "}
												of {orders.length} orders
											</div>
										</div>
									)}
								</div>
							</div>

							{/* Wishlist Tab */}
							<div
								className={
									activeTab === "wishlist"
										? "tab-pane fade show active"
										: "tab-pane fade"
								}
								id="wishlist"
								role="tabpanel"
								aria-labelledby="wishlist-tab"
							>
								<div className="tab-header">
									<h2>Wishlist</h2>
								</div>
								<div className="wishlist-items">
									<div className="row">
										{/* Wishlist Item 1 */}
										<div
											className="col-md-6 col-lg-4 mb-4 aos-init aos-animate"
											data-aos="fade-up"
											data-aos-delay="100"
										>
											<div className="wishlist-item">
												<div className="wishlist-image">
													<img
														src="assets/img/product/product-1.webp"
														alt="Product"
														loading="lazy"
													/>
													<button
														className="remove-wishlist"
														type="button"
													>
														<i className="bi bi-x-lg"></i>
													</button>
												</div>
												<div className="wishlist-content">
													<h5>
														Lorem ipsum dolor sit
														amet
													</h5>
													<div className="product-price">
														$129.99
													</div>
													<button className="btn btn-add-cart">
														Add to cart
													</button>
												</div>
											</div>
										</div>
										{/* End Wishlist Item */}

										{/* Wishlist Item 2 */}
										<div
											className="col-md-6 col-lg-4 mb-4 aos-init aos-animate"
											data-aos="fade-up"
											data-aos-delay="200"
										>
											<div className="wishlist-item">
												<div className="wishlist-image">
													<img
														src="assets/img/product/product-2.webp"
														alt="Product"
														loading="lazy"
													/>
													<button
														className="remove-wishlist"
														type="button"
													>
														<i className="bi bi-x-lg"></i>
													</button>
												</div>
												<div className="wishlist-content">
													<h5>
														Consectetur adipiscing
														elit
													</h5>
													<div className="product-price">
														$89.50
													</div>
													<button className="btn btn-add-cart">
														Add to cart
													</button>
												</div>
											</div>
										</div>
										{/* End Wishlist Item */}

										{/* Wishlist Item 3 */}
										<div
											className="col-md-6 col-lg-4 mb-4 aos-init aos-animate"
											data-aos="fade-up"
											data-aos-delay="300"
										>
											<div className="wishlist-item">
												<div className="wishlist-image">
													<img
														src="assets/img/product/product-3.webp"
														alt="Product"
														loading="lazy"
													/>
													<button
														className="remove-wishlist"
														type="button"
													>
														<i className="bi bi-x-lg"></i>
													</button>
												</div>
												<div className="wishlist-content">
													<h5>
														Sed do eiusmod tempor
													</h5>
													<div className="product-price">
														$199.99
													</div>
													<button className="btn btn-add-cart">
														Add to cart
													</button>
												</div>
											</div>
										</div>
										{/* End Wishlist Item */}
									</div>
								</div>
							</div>

							{/* Payment Methods Tab */}
							<div
								className={
									activeTab === "payment"
										? "tab-pane fade show active"
										: "tab-pane fade"
								}
								id="payment"
								role="tabpanel"
								aria-labelledby="payment-tab"
							>
								<div className="tab-header">
									<h2>Payment Methods</h2>
									<button
										className="btn btn-add-payment"
										type="button"
									>
										<i className="bi bi-plus-lg"></i> Add
										payment method
									</button>
								</div>
								<div className="payment-methods">
									{/* Payment Method 1 */}
									<div
										className="payment-method-item aos-init aos-animate"
										data-aos="fade-up"
										data-aos-delay="100"
									>
										<div className="payment-card">
											<div className="card-type">
												<i className="bi bi-credit-card"></i>
											</div>
											<div className="card-info">
												<div className="card-number">
													**** **** **** 4589
												</div>
												<div className="card-expiry">
													Expires 09/2026
												</div>
											</div>
											<div className="card-actions">
												<button
													className="btn-edit-card"
													type="button"
												>
													<i className="bi bi-pencil"></i>
												</button>
												<button
													className="btn-delete-card"
													type="button"
												>
													<i className="bi bi-trash"></i>
												</button>
											</div>
										</div>
										<div className="default-badge">
											Default
										</div>
									</div>
									{/* End Payment Method */}

									{/* Payment Method 2 */}
									<div
										className="payment-method-item aos-init aos-animate"
										data-aos="fade-up"
										data-aos-delay="200"
									>
										<div className="payment-card">
											<div className="card-type">
												<i className="bi bi-credit-card"></i>
											</div>
											<div className="card-info">
												<div className="card-number">
													**** **** **** 7821
												</div>
												<div className="card-expiry">
													Expires 05/2025
												</div>
											</div>
											<div className="card-actions">
												<button
													className="btn-edit-card"
													type="button"
												>
													<i className="bi bi-pencil"></i>
												</button>
												<button
													className="btn-delete-card"
													type="button"
												>
													<i className="bi bi-trash"></i>
												</button>
											</div>
										</div>
										<button
											className="btn btn-sm btn-make-default"
											type="button"
										>
											Make default
										</button>
									</div>
									{/* End Payment Method */}
								</div>
							</div>

							{/* Reviews Tab */}
							<div
								className={
									activeTab === "reviews"
										? "tab-pane fade show active"
										: "tab-pane fade"
								}
								id="reviews"
								role="tabpanel"
								aria-labelledby="reviews-tab"
							>
								<div className="tab-header">
									<h2>My Reviews</h2>
								</div>
								<div className="reviews-list">
									{/* Review Item 1 */}
									<div
										className="review-item aos-init aos-animate"
										data-aos="fade-up"
										data-aos-delay="100"
									>
										<div className="review-header">
											<div className="review-product">
												<img
													src="assets/img/product/product-4.webp"
													alt="Product"
													className="product-image"
													loading="lazy"
												/>
												<div className="product-info">
													<h5>
														Lorem ipsum dolor sit
														amet
													</h5>
													<div className="review-date">
														Reviewed on 01/15/2025
													</div>
												</div>
											</div>
											<div className="review-rating">
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star"></i>
											</div>
										</div>
										<div className="review-content">
											<p>
												Lorem ipsum dolor sit amet,
												consectetur adipiscing elit.
												Nullam auctor, nisl eget
												ultricies tincidunt, nisl nisl
												aliquam nisl, eget ultricies
												nisl nisl eget nisl.
											</p>
										</div>
										<div className="review-actions">
											<button
												className="btn btn-sm btn-edit-review"
												type="button"
											>
												Edit
											</button>
											<button
												className="btn btn-sm btn-delete-review"
												type="button"
											>
												Delete
											</button>
										</div>
									</div>
									{/* End Review Item */}

									{/* Review Item 2 */}
									<div
										className="review-item aos-init aos-animate"
										data-aos="fade-up"
										data-aos-delay="200"
									>
										<div className="review-header">
											<div className="review-product">
												<img
													src="assets/img/product/product-5.webp"
													alt="Product"
													className="product-image"
													loading="lazy"
												/>
												<div className="product-info">
													<h5>
														Consectetur adipiscing
														elit
													</h5>
													<div className="review-date">
														Reviewed on 12/03/2024
													</div>
												</div>
											</div>
											<div className="review-rating">
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
												<i className="bi bi-star-fill"></i>
											</div>
										</div>
										<div className="review-content">
											<p>
												Sed do eiusmod tempor incididunt
												ut labore et dolore magna
												aliqua. Ut enim ad minim veniam,
												quis nostrud exercitation
												ullamco laboris nisi ut aliquip
												ex ea commodo consequat.
											</p>
										</div>
										<div className="review-actions">
											<button
												className="btn btn-sm btn-edit-review"
												type="button"
											>
												Edit
											</button>
											<button
												className="btn btn-sm btn-delete-review"
												type="button"
											>
												Delete
											</button>
										</div>
									</div>
									{/* End Review Item */}
								</div>
							</div>

							{/* Addresses Tab */}
							<div
								className={
									activeTab === "addresses"
										? "tab-pane fade show active"
										: "tab-pane fade"
								}
								id="addresses"
								role="tabpanel"
								aria-labelledby="addresses-tab"
							>
								<div className="tab-header">
									<h2>My Addresses</h2>
									<button
										className="btn btn-add-address"
										type="button"
										data-bs-toggle="modal"
										data-bs-target="#addAddressModal"
									>
										<i className="bi bi-plus-lg"></i> Add
										new address
									</button>
								</div>

								{/* Add Address Modal */}
								<div
									className="modal fade"
									id="addAddressModal"
									tabIndex="-1"
									aria-labelledby="addAddressModalLabel"
									aria-hidden="true"
								>
									<div className="modal-dialog">
										<div className="modal-content">
											<div className="modal-header">
												<h5
													className="modal-title"
													id="addAddressModalLabel"
												>
													Add New Address
												</h5>
												<button
													type="button"
													className="btn-close"
													data-bs-dismiss="modal"
													aria-label="Close"
												></button>
											</div>
											<form
												id="addAddressForm"
												onSubmit={saveNewAddress}
											>
												<div className="modal-body">
													{formErrors.submit && (
														<div className="alert alert-danger">
															{formErrors.submit}
														</div>
													)}

													<div className="mb-3">
														<label
															htmlFor="addressLabel"
															className="form-label"
														>
															Address Label
														</label>
														<input
															type="text"
															className="form-control"
															id="addressLabel"
															value={
																newAddress?.addressLabel ||
																""
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	addressLabel:
																		e.target
																			.value,
																})
															}
															placeholder="e.g., Home, Work"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="fullName"
															className="form-label"
														>
															Full Name
														</label>
														<input
															type="text"
															className="form-control"
															id="fullName"
															value={
																newAddress?.fullName ||
																""
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	fullName:
																		e.target
																			.value,
																})
															}
															placeholder="John Doe"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="phoneNumber"
															className="form-label"
														>
															Phone Number
														</label>
														<input
															type="tel"
															className="form-control"
															id="phoneNumber"
															value={
																newAddress?.phoneNumber ||
																""
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	phoneNumber:
																		e.target
																			.value,
																})
															}
															placeholder="071 234 5678"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="addressLine1"
															className="form-label"
														>
															Address Line 1
														</label>
														<input
															type="text"
															className="form-control"
															id="addressLine1"
															value={
																newAddress?.addressLine1 ||
																""
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	addressLine1:
																		e.target
																			.value,
																})
															}
															placeholder="123 Main Street"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="addressLine2"
															className="form-label"
														>
															Address Line 2
															(optional)
														</label>
														<input
															type="text"
															className="form-control"
															id="addressLine2"
															value={
																newAddress?.addressLine2 ||
																""
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	addressLine2:
																		e.target
																			.value,
																})
															}
															placeholder="Apt 4B"
														/>
													</div>
													<div className="row">
														<div className="col-md-6 mb-3">
															<label
																htmlFor="city"
																className="form-label"
															>
																City
															</label>
															<input
																type="text"
																className="form-control"
																id="city"
																value={
																	newAddress?.city ||
																	""
																}
																onChange={(e) =>
																	setNewAddress(
																		{
																			...newAddress,
																			city: e
																				.target
																				.value,
																		}
																	)
																}
																required
															/>
														</div>
														<div className="col-md-6 mb-3">
															<label
																htmlFor="state"
																className="form-label"
															>
																State/Province
															</label>
															<input
																type="text"
																className="form-control"
																id="state"
																value={
																	newAddress?.state ||
																	""
																}
																onChange={(e) =>
																	setNewAddress(
																		{
																			...newAddress,
																			state: e
																				.target
																				.value,
																		}
																	)
																}
																required
															/>
														</div>
													</div>
													<div className="row">
														<div className="col-md-6 mb-3">
															<label
																htmlFor="zipCode"
																className="form-label"
															>
																ZIP/Postal Code
															</label>
															<input
																type="text"
																className="form-control"
																id="zipCode"
																value={
																	newAddress?.zipCode ||
																	""
																}
																onChange={(e) =>
																	setNewAddress(
																		{
																			...newAddress,
																			zipCode:
																				e
																					.target
																					.value,
																		}
																	)
																}
																required
															/>
														</div>
														<div className="col-md-6 mb-3">
															<label
																htmlFor="type"
																className="form-label"
															>
																Address Type
															</label>
															<select
																className="form-select"
																id="type"
																value={
																	newAddress?.type ||
																	""
																}
																onChange={(e) =>
																	setNewAddress(
																		{
																			...newAddress,
																			type: e
																				.target
																				.value,
																		}
																	)
																}
																required
															>
																<option value="">
																	Select Type
																</option>
																<option value="shipping">
																	Shipping
																</option>
																<option value="billing">
																	Billing
																</option>
																<option value="both">
																	Billing and
																	Shipping
																</option>
															</select>
														</div>
													</div>
													<div className="mb-3 form-check">
														<input
															type="checkbox"
															className="form-check-input"
															id="defaultAddress"
															checked={
																newAddress?.isDefault ||
																false
															}
															onChange={(e) =>
																setNewAddress({
																	...newAddress,
																	isDefault:
																		e.target
																			.checked,
																})
															}
														/>
														<label
															className="form-check-label"
															htmlFor="defaultAddress"
														>
															Set as default
															address
														</label>
													</div>
												</div>
												<div className="modal-footer">
													<button
														type="button"
														className="btn btn-secondary"
														data-bs-dismiss="modal"
													>
														Cancel
													</button>
													<button
														type="submit"
														className="btn btn-save"
														style={{
															backgroundColor:
																"var(--accent-color)",
															color: "white",
														}}
													>
														Save Address
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								{/* Edit Address Modal */}
								<div
									className="modal fade"
									id="editAddressModal"
									tabIndex="-1"
									aria-labelledby="editAddressModalLabel"
									aria-hidden="true"
								>
									<div className="modal-dialog">
										<div className="modal-content">
											<div className="modal-header">
												<h5
													className="modal-title"
													id="editAddressModalLabel"
												>
													Edit Address
												</h5>
												<button
													type="button"
													className="btn-close"
													data-bs-dismiss="modal"
													aria-label="Close"
												></button>
											</div>
											<form
												id="editAddressForm"
												onSubmit={updateAddress}
											>
												<div className="modal-body">
													{formErrors.submit && (
														<div className="alert alert-danger">
															{formErrors.submit}
														</div>
													)}

													<div className="mb-3">
														<label
															htmlFor="editAddressLabel"
															className="form-label"
														>
															Address Label
														</label>
														<input
															type="text"
															className="form-control"
															id="editAddressLabel"
															value={
																editingAddress?.addressLabel ||
																""
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		addressLabel:
																			e
																				.target
																				.value,
																	}
																)
															}
															placeholder="e.g., Home, Work"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="editFullName"
															className="form-label"
														>
															Full Name
														</label>
														<input
															type="text"
															className="form-control"
															id="editFullName"
															value={
																editingAddress?.fullName ||
																""
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		fullName:
																			e
																				.target
																				.value,
																	}
																)
															}
															placeholder="John Doe"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="editPhoneNumber"
															className="form-label"
														>
															Phone Number
														</label>
														<input
															type="tel"
															className="form-control"
															id="editPhoneNumber"
															value={
																editingAddress?.phoneNumber ||
																""
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		phoneNumber:
																			e
																				.target
																				.value,
																	}
																)
															}
															placeholder="071 234 5678"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="editAddressLine1"
															className="form-label"
														>
															Address Line 1
														</label>
														<input
															type="text"
															className="form-control"
															id="editAddressLine1"
															value={
																editingAddress?.addressLine1 ||
																""
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		addressLine1:
																			e
																				.target
																				.value,
																	}
																)
															}
															placeholder="123 Main Street"
															required
														/>
													</div>
													<div className="mb-3">
														<label
															htmlFor="editAddressLine2"
															className="form-label"
														>
															Address Line 2
															(optional)
														</label>
														<input
															type="text"
															className="form-control"
															id="editAddressLine2"
															value={
																editingAddress?.addressLine2 ||
																""
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		addressLine2:
																			e
																				.target
																				.value,
																	}
																)
															}
															placeholder="Apt 4B"
														/>
													</div>
													<div className="row">
														<div className="col-md-6 mb-3">
															<label
																htmlFor="editCity"
																className="form-label"
															>
																City
															</label>
															<input
																type="text"
																className="form-control"
																id="editCity"
																value={
																	editingAddress?.city ||
																	""
																}
																onChange={(e) =>
																	setEditingAddress(
																		{
																			...editingAddress,
																			city: e
																				.target
																				.value,
																		}
																	)
																}
																required
															/>
														</div>
														<div className="col-md-6 mb-3">
															<label
																htmlFor="editState"
																className="form-label"
															>
																State/Province
															</label>
															<input
																type="text"
																className="form-control"
																id="editState"
																value={
																	editingAddress?.state ||
																	""
																}
																onChange={(e) =>
																	setEditingAddress(
																		{
																			...editingAddress,
																			state: e
																				.target
																				.value,
																		}
																	)
																}
																required
															/>
														</div>
													</div>
													<div className="row">
														<div className="col-md-6 mb-3">
															<label
																htmlFor="editZipCode"
																className="form-label"
															>
																ZIP/Postal Code
															</label>
															<input
																type="text"
																className="form-control"
																id="editZipCode"
																value={
																	editingAddress?.zipCode ||
																	""
																}
																onChange={(e) =>
																	setEditingAddress(
																		{
																			...editingAddress,
																			zipCode:
																				e
																					.target
																					.value,
																		}
																	)
																}
																required
															/>
														</div>
														<div className="col-md-6 mb-3">
															<label
																htmlFor="editType"
																className="form-label"
															>
																Address Type
															</label>
															<select
																className="form-select"
																id="editType"
																value={
																	editingAddress?.type ||
																	""
																}
																onChange={(e) =>
																	setEditingAddress(
																		{
																			...editingAddress,
																			type: e
																				.target
																				.value,
																		}
																	)
																}
																required
															>
																<option value="">
																	Select Type
																</option>
																<option value="shipping">
																	Shipping
																</option>
																<option value="billing">
																	Billing
																</option>
																<option value="both">
																	Billing and
																	Shipping
																</option>
															</select>
														</div>
													</div>
													<div className="mb-3 form-check">
														<input
															type="checkbox"
															className="form-check-input"
															id="editDefaultAddress"
															checked={
																editingAddress?.isDefault ||
																false
															}
															onChange={(e) =>
																setEditingAddress(
																	{
																		...editingAddress,
																		isDefault:
																			e
																				.target
																				.checked,
																	}
																)
															}
														/>
														<label
															className="form-check-label"
															htmlFor="editDefaultAddress"
														>
															Set as default
															address
														</label>
													</div>
												</div>
												<div className="modal-footer">
													<button
														type="button"
														className="btn btn-secondary"
														data-bs-dismiss="modal"
													>
														Cancel
													</button>
													<button
														type="submit"
														className="btn btn-save"
														style={{
															backgroundColor:
																"var(--accent-color)",
															color: "white",
														}}
													>
														Update Address
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								{/* Delete Address Confirmation Modal */}
								{addresses &&
									addresses.map((address) => (
										<div
											key={`modal-${address.id}`}
											className="modal fade"
											id={`deleteModal${address.id}`}
											tabIndex="-1"
											aria-labelledby={`deleteModalLabel${address.id}`}
											aria-hidden="true"
										>
											<div className="modal-dialog">
												<div className="modal-content">
													<div className="modal-header">
														<h5
															className="modal-title"
															id={`deleteModalLabel${address.id}`}
														>
															Delete Address
														</h5>
														<button
															type="button"
															className="btn-close"
															data-bs-dismiss="modal"
															aria-label="Close"
														></button>
													</div>
													<div className="modal-body">
														Are you sure you want to
														delete this address?
													</div>
													<div className="modal-footer">
														<button
															type="button"
															className="btn btn-secondary btn-cancel-delete"
															data-bs-dismiss="modal"
														>
															Cancel
														</button>
														<button
															type="button"
															className="btn btn-danger btn-confirm-delete"
														>
															Delete
														</button>
													</div>
												</div>
											</div>
										</div>
									))}

								<div className="addresses-list">
									{successMsg.submit && (
										<div className="alert alert-success">
											{successMsg.submit}
										</div>
									)}

									<div className="row">
										{addresses && addresses.length > 0 ? (
											addresses.map((address, index) => (
												<div
													key={address.id}
													className="col-lg-6 mb-4 aos-init aos-animate"
													data-aos="fade-up"
													data-aos-delay={
														100 + index * 100
													}
												>
													<div className="address-item">
														<div className="address-header">
															<h5>
																{address.label}{" "}
																<span className="badge bg-secondary text-capitalize ms-2">
																	{address.type ===
																	"both"
																		? "Shipping & Billing"
																		: address.type}
																</span>
															</h5>
															<div className="address-actions">
																<button
																	className="btn-edit-address"
																	type="button"
																	onClick={() =>
																		handleEditAddress(
																			address
																		)
																	}
																	data-bs-toggle="modal"
																	data-bs-target="#editAddressModal"
																>
																	<i className="bi bi-pencil"></i>
																</button>
																<button
																	className="btn-delete-address"
																	type="button"
																	onClick={() =>
																		handleDeleteAddress(
																			address.id
																		)
																	}
																>
																	<i className="bi bi-trash"></i>
																</button>
															</div>
														</div>
														<div className="address-content">
															<p>
																<strong>
																	{
																		address.full_name
																	}
																</strong>
																<br />
																{
																	address.phone_number
																}
																<br />
																{
																	address.address_line1
																}
																<br />
																{address.address_line2 && (
																	<>
																		{
																			address.address_line2
																		}
																		<br />
																	</>
																)}
																{address.city},{" "}
																{address.state}{" "}
																{
																	address.postal_code
																}
															</p>
														</div>
														{address.is_default ===
														1 ? (
															<div className="default-badge">
																Default
															</div>
														) : (
															<button
																className="btn btn-sm btn-make-default"
																type="button"
																onClick={() =>
																	handleMakeDefault(
																		address.id
																	)
																}
															>
																Make default
															</button>
														)}
													</div>
												</div>
											))
										) : (
											<div className="col-12">
												<p className="text-center">
													No addresses found. Add a
													new address to get started.
												</p>
											</div>
										)}
									</div>
								</div>
							</div>

							{/* Notifications Tab */}
							<div
								className={
									activeTab === "notifications"
										? "tab-pane fade show active"
										: "tab-pane fade"
								}
								id="notifications"
								role="tabpanel"
								aria-labelledby="notifications-tab"
							>
								<div className="tab-header">
									<h2>Notification Settings</h2>
								</div>
								<div
									className="notifications-settings aos-init aos-animate"
									data-aos="fade-up"
									data-aos-delay="100"
								>
									<div className="notification-group">
										<h5>Order Updates</h5>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Order status changes
												</div>
												<div className="notification-desc">
													Receive notifications when
													your order status changes
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="orderStatusNotif"
													checked={
														user?.notifications
															?.orderStatus
													}
													onChange={(e) =>
														setUser({
															...user,
															notifications: {
																...user.notifications,
																orderStatus:
																	e.target
																		.checked,
															},
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="orderStatusNotif"
												></label>
											</div>
										</div>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Shipping updates
												</div>
												<div className="notification-desc">
													Receive notifications about
													shipping and delivery
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="shippingNotif"
													checked={
														user?.notifications
															?.shipping
													}
													onChange={(e) =>
														setUser({
															...user,
															notifications: {
																...user.notifications,
																shipping:
																	e.target
																		.checked,
															},
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="shippingNotif"
												></label>
											</div>
										</div>
									</div>

									<div className="notification-group">
										<h5>Account Activity</h5>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Security alerts
												</div>
												<div className="notification-desc">
													Receive notifications about
													security-related activity
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="securityNotif"
													checked={
														user?.notifications
															?.security
													}
													onChange={(e) =>
														setUser({
															...user,
															notifications: {
																...user.notifications,
																security:
																	e.target
																		.checked,
															},
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="securityNotif"
												></label>
											</div>
										</div>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Password changes
												</div>
												<div className="notification-desc">
													Receive notifications when
													your password is changed
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="passwordNotif"
													checked={
														user?.notifications
															?.password
													}
													onChange={(e) =>
														setUser({
															...user,
															notifications: {
																...user.notifications,
																password:
																	e.target
																		.checked,
															},
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="passwordNotif"
												></label>
											</div>
										</div>
									</div>

									<div className="notification-group">
										<h5>Marketing</h5>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Promotions and deals
												</div>
												<div className="notification-desc">
													Receive notifications about
													special offers and discounts
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="promoNotif"
												/>
												<label
													className="form-check-label"
													htmlFor="promoNotif"
												></label>
											</div>
										</div>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													New product arrivals
												</div>
												<div className="notification-desc">
													Receive notifications when
													new products are added
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="newProductNotif"
												/>
												<label
													className="form-check-label"
													htmlFor="newProductNotif"
												></label>
											</div>
										</div>
										<div className="notification-item">
											<div className="notification-info">
												<div className="notification-title">
													Personalized recommendations
												</div>
												<div className="notification-desc">
													Receive notifications with
													product recommendations
													based on your interests
												</div>
											</div>
											<div className="form-check form-switch">
												<input
													className="form-check-input"
													type="checkbox"
													id="recommendNotif"
													checked={
														user?.notifications
															?.recommend
													}
													onChange={(e) =>
														setUser({
															...user,
															notifications: {
																...user.notifications,
																recommend:
																	e.target
																		.checked,
															},
														})
													}
												/>
												<label
													className="form-check-label"
													htmlFor="recommendNotif"
												></label>
											</div>
										</div>
									</div>

									<div className="notification-actions">
										<button
											type="button"
											className="btn btn-save"
										>
											Save Preferences
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	);
}

export default Profile;
