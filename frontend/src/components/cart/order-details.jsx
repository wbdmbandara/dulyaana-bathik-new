import React, { useState, useEffect, use } from "react";
import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";
import { useNavigate } from "react-router-dom";
import { useSnackbar } from "../../context/SnackbarContext";

function OrderDetails() {
	const navigate = useNavigate();
	const [orderID, setOrderID] = useState(null);
	const [orderData, setOrderData] = useState(null);
	const [orderedItems, setOrderedItems] = useState([]);
	const { showSnackbar } = useSnackbar();

	// Get order ID from URL
	useEffect(() => {
		const queryParams = new URLSearchParams(window.location.search);
		const orderId = queryParams.get("order_id");
		if (orderId) {
			setOrderID(orderId);
		}
	}, []);

	useEffect(() => {
		const storedUser = localStorage.getItem("user");
		if (!storedUser) {
			navigate("/login?redirect=/order-details?order_id=" + orderID);
		}
	}, []);

	var customerID = JSON.parse(localStorage.getItem("user"))?.id;

	// Get order items
	useEffect(() => {
		if (!orderID) return;

		const fetchOrderItems = async () => {
			try {
				fetch(`${API_URL}order-details/${orderID}`, {
					method: "GET",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"auth_token"
						)}`,
					},
				})
					.then((response) => {
						if (!response.ok) {
							showSnackbar(
								"Failed to fetch order items",
								"error"
							);
							throw new Error("Failed to fetch order items");
						}

						return response.json();
					})
					.then((data) => {
						setOrderData(data.order_data);
						setOrderedItems(data.ordered_items);
					})
					.catch((error) => {
						console.error(error);
						showSnackbar("Failed to fetch order items", "error");
					});
			} catch (error) {
				console.error("Error fetching order items:", error);
				showSnackbar("Error fetching order items", "error");
			}
		};
		fetchOrderItems();
	}, [orderID]);

	// Helper function to get status badge class
	const getStatusBadgeClass = (status) => {
		switch (status?.toLowerCase()) {
			case "pending":
				return "bg-warning text-dark";
			case "processing":
				return "bg-info text-white";
			case "shipped":
				return "bg-primary text-white";
			case "delivered":
				return "bg-success text-white";
			case "cancelled":
				return "bg-danger text-white";
			default:
				return "bg-secondary text-white";
		}
	};

	// Helper function to format payment method display
	const formatPaymentMethod = (method) => {
		switch (method?.toLowerCase()) {
			case "credit-card":
				return "Credit Card";
			case "bank-transfer":
				return "Bank Transfer";
			case "cash-on-delivery":
				return "Cash on Delivery";
			case "paypal":
				return "PayPal";
			case "apple-pay":
				return "Apple Pay";
			default:
				return method || "N/A";
		}
	};

	return (
		<section id="cart" className="cart section">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
				{orderedItems.length > 0 ? (
					<>
						{/* Order Header with Status */}
						<div className="row mb-4">
							<div className="col-12">
								<div className="d-flex justify-content-between align-items-center flex-wrap">
									<div>
										<h3 className="mb-2">
											Order Details
										</h3>
										<p className="text-muted mb-0">
											Order ID: #{orderID}
										</p>
									</div>
									<div className="mt-2 mt-md-0">
										<span
											className={`badge ${getStatusBadgeClass(
												orderData?.status
											)} px-3 py-2 fs-6`}
										>
											{orderData?.status
												? orderData.status.toUpperCase()
												: "PENDING"}
										</span>
									</div>
								</div>
								<hr className="mt-3" />
							</div>
						</div>

						<div className="row">
							<div
								className="col-lg-8 aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="200"
							>
								<div className="cart-items">
									<div className="cart-header d-none d-lg-block">
										<div className="row align-items-center">
											<div className="col-lg-6">
												<h5>Product</h5>
											</div>
											<div className="col-lg-2 text-center">
												<h5>Price</h5>
											</div>
											<div className="col-lg-2 text-center">
												<h5>Quantity</h5>
											</div>
											<div className="col-lg-2 text-center">
												<h5>Total</h5>
											</div>
										</div>
									</div>

									{orderedItems.map((item) => (
										<div
											key={item.item_id}
											className="cart-item"
										>
											<div className="row align-items-center">
												<div className="col-lg-6 col-12 mt-3 mt-lg-0 mb-lg-0 mb-3">
													<div className="product-info d-flex align-items-center">
														<div className="product-image">
															<img
																src={
																	BACKEND_URL +
																	item.main_image
																}
																alt={item.name}
																className="img-fluid"
																loading="lazy"
															/>
														</div>
														<div className="product-details">
															<h6 className="product-title">
																{item.name}
															</h6>
															<div className="product-meta d-none">
																<span className="product-color">
																	Color:{" "}
																	{item.color}
																</span>
																<span className="product-size">
																	Size:{" "}
																	{item.size}
																</span>
															</div>
														</div>
													</div>
												</div>
												<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
													<div className="price-tag">
														<span className="current-price d-block">
															{formatCurrency(
																item?.price
															)}
														</span>
													</div>
												</div>
												<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
													<span>{item.quantity}</span>
												</div>
												<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
													<div className="item-total">
														<span>
															{formatCurrency(
																item?.value
															)}
														</span>
													</div>
												</div>
											</div>
										</div>
									))}
								</div>
							</div>

							<div
								className="col-lg-4 mt-4 mt-lg-0 aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="300"
							>
								{" "}
								<div className="cart-summary">
									<h4 className="summary-title">
										Order Summary
									</h4>

									<div className="summary-item">
										<span className="summary-label">
											Order ID
										</span>
										<span className="summary-value">
											#{orderID}
										</span>
									</div>

									<div className="summary-item">
										<span className="summary-label">
											Payment Method
										</span>
										<span className="summary-value">
											<i
												className={`bi ${
													orderData?.payment_method ===
													"credit-card"
														? "bi-credit-card"
														: orderData?.payment_method ===
														  "bank-transfer"
														? "bi-bank"
														: orderData?.payment_method ===
														  "cash-on-delivery"
														? "bi-cash"
														: orderData?.payment_method ===
														  "paypal"
														? "bi-paypal"
														: "bi-wallet"
												} me-2`}
											></i>
											{formatPaymentMethod(
												orderData?.payment_method
											)}
										</span>
									</div>

									<hr className="my-3" />

									<div className="summary-item">
										<span className="summary-label">
											Subtotal
										</span>
										<span className="summary-value">
											{formatCurrency(
												orderData?.total_amount
											)}
										</span>
									</div>

									<div className="summary-item shipping-item">
										<span className="summary-label">
											Shipping
										</span>
										<span className="summary-value">
											{formatCurrency(
												orderData?.shipping_fee
											)}
										</span>
									</div>

									<div className="summary-item discount">
										<span className="summary-label">
											Discount
										</span>
										<span className="summary-value">
											-{formatCurrency(0)}
										</span>
									</div>

									<div className="summary-total">
										<span className="summary-label">
											Total
										</span>
										<span className="summary-value">
											{formatCurrency(
												orderData?.final_amount
											)}
										</span>{" "}
									</div>
								</div>
							</div>
						</div>
					</>
				) : (
					<div className="empty-cart text-center">
						<h3>Your cart is empty</h3>
						<p>
							Looks like you haven't added anything to your cart
							yet.
						</p>
						<a href="/shop" className="btn btn-accent">
							Shop Now
						</a>
					</div>
				)}
			</div>
		</section>
	);
}

export default OrderDetails;
