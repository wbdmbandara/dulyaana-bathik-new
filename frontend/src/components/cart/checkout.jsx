import React, { useState, useEffect, use } from "react";
import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";
import { useNavigate } from "react-router-dom";
import { useSnackbar } from "../../context/SnackbarContext";
import "./checkout.css";

function Checkout() {
	const navigate = useNavigate();
	// State for checkout items
	const [checkoutItems, setCheckoutItems] = useState([]);
	const [isLoading, setIsLoading] = useState(true);
	const [customerData, setCustomerData] = useState(null);
	const [shippingData, setShippingData] = useState(null);
	const [paymentData, setPaymentData] = useState({
		method: "bank-transfer",
		displayName: "Bank Transfer",
	});
	const [bankDetails, setBankDetails] = useState([]);
	const [addresses, setAddresses] = useState([]);
	const [currentStep, setCurrentStep] = useState(1);
	const [isSubmitting, setIsSubmitting] = useState(false);
	const { showSnackbar } = useSnackbar();

	useEffect(() => {
		const storedUser = localStorage.getItem("user");
		if (!storedUser) {
			navigate("/login?redirect=/checkout");
		}
	}, []);

	var customerID = JSON.parse(localStorage.getItem("user"))?.id;

	// Get checkout items
	useEffect(() => {
		const fetchCheckoutItems = async () => {
			try {
				fetch(`${API_URL}cart`, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem(
							"auth_token"
						)}`,
					},
					body: JSON.stringify({
						customer_id: customerID,
					}),
				})
					.then((response) => {
						if (!response.ok) {
							showSnackbar("Failed to fetch checkout items", "error");
							throw new Error("Failed to fetch checkout items");
						}

						return response.json();
					})
					.then((data) => {
						// console.log(data);
						// if cart is empty, redirect to shop page
						if (data.cart_items.length === 0) {
							showSnackbar("Your cart is empty. Please add items to proceed to checkout.", "info");
							navigate("/shop");
							return;
						}
						setCheckoutItems(data.cart_items);
					})
					.catch((error) => {
						console.error(error);
						showSnackbar("Failed to fetch checkout items", "error");
					});
			} catch (error) {
				console.error("Error fetching checkout items:", error);
				showSnackbar("Error fetching checkout items", "error");
			}
		};
		fetchCheckoutItems();
	}, []);

	// Get customer data
	useEffect(() => {
		const fetchCustomerData = async () => {
			try {
				const response = await fetch(`${API_URL}user/profile`, {
					headers: {
						Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
					},
				});
				if (response.success === false || !response.ok) {
					showSnackbar("Failed to fetch customer data", "error");
					throw new Error("Failed to fetch customer data");
				}
				const data = await response.json();
				setCustomerData(data.user);
			} catch (error) {
				console.error("Error fetching customer data:", error);
				showSnackbar("Error fetching customer data", "error");
			}
		};
		if (customerID) {
			fetchCustomerData();
		}
	}, [customerID]);

	// Fetch customer addresses
	useEffect(() => {
		const fetchAddresses = async (id) => {
			try {
				const response = await fetch(
					`${API_URL}customerAddresses/${id}`,
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
					throw new Error("Failed to fetch addresses");
				}
				const data = await response.json();
				setAddresses(data.addresses);
				// console.log(data.addresses);
			} catch (error) {
				console.error(error);
			}
		};
		if (customerID) {
			fetchAddresses(customerID);
		}
	}, [customerID]);

	// Fetch Payment Bank Details
	useEffect(() => {
		const fetchPaymentBankDetails = async () => {
			try {
				const response = await fetch(`${API_URL}getBankDetails`, {
					headers: {
						Authorization: `Bearer ${localStorage.getItem(
							"auth_token"
						)}`,
					},
				});
				if (!response.ok) {
					showSnackbar(
						"Failed to fetch payment bank details",
						"error"
					);
					throw new Error("Failed to fetch payment bank details");
				}
				const data = await response.json();
				setBankDetails(data.data);
			} catch (error) {
				console.error("Error fetching payment bank details:", error);
				showSnackbar("Error fetching payment bank details", "error");
			}
		};
		fetchPaymentBankDetails();
	}, []);

	// Calculate totals
	const subtotal = checkoutItems.reduce(
		(sum, item) =>
			sum +
			(item?.discount_price > 0
				? item?.discount_price
				: item?.item_price) *
				item.quantity,
		0
	);

	const shipping = 4.99; // Default shipping
	const total = subtotal + shipping;

	// Validation functions for each step
	const validateStep1 = () => {
		const name = document.getElementById("name")?.value?.trim();
		const email = document.getElementById("email")?.value?.trim();
		const phone = document.getElementById("phone")?.value?.trim();

		if (!name) {
			showSnackbar("Please enter your name", "error");
			document.getElementById("name")?.focus();
			return false;
		}

		if (!email) {
			showSnackbar("Please enter your email address", "error");
			document.getElementById("email")?.focus();
			return false;
		}

		// Basic email validation
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (!emailRegex.test(email)) {
			showSnackbar("Please enter a valid email address", "error");
			document.getElementById("email")?.focus();
			return false;
		}

		if (!phone) {
			showSnackbar("Please enter your phone number", "error");
			document.getElementById("phone")?.focus();
			return false;
		}

		// Save customer data
		setCustomerData({ name, email, phone });
		return true;
	};

	const validateStep2 = () => {
		const fullName = document.getElementById("full-name")?.value?.trim();
		const phoneNumber = document
			.getElementById("phone-number")
			?.value?.trim();
		const addressLine1 = document
			.getElementById("address-line1")
			?.value?.trim();
		const addressLine2 = document
			.getElementById("address-line2")
			?.value?.trim();
		const city = document.getElementById("city")?.value?.trim();
		const state = document.getElementById("state")?.value?.trim();
		const postalCode = document
			.getElementById("postal-code")
			?.value?.trim();
		const addressLabel = document
			.getElementById("address-label")
			?.value?.trim();

		if (!fullName) {
			showSnackbar("Please enter your full name", "error");
			document.getElementById("full-name")?.focus();
			return false;
		}
		if (!phoneNumber) {
			showSnackbar("Please enter your phone number", "error");
			document.getElementById("phone-number")?.focus();
			return false;
		}

		if (!addressLine1) {
			showSnackbar("Please enter your address line 1", "error");
			document.getElementById("address-line1")?.focus();
			return false;
		}

		if (!city) {
			showSnackbar("Please enter your city", "error");
			document.getElementById("city")?.focus();
			return false;
		}

		if (!state) {
			showSnackbar("Please enter your state/province", "error");
			document.getElementById("state")?.focus();
			return false;
		}

		if (!postalCode) {
			showSnackbar("Please enter your ZIP/postal code", "error");
			document.getElementById("postal-code")?.focus();
			return false;
		}

		if (!addressLabel) {
			showSnackbar("Please enter a label for your address", "error");
			document.getElementById("address-label")?.focus();
			return false;
		}

		// Save shipping data
		setShippingData({
			fullName,
			phoneNumber,
			addressLine1,
			addressLine2,
			city,
			state,
			postalCode,
			addressLabel,
		});
		return true;
	};

	const validateStep3 = () => {
		const paymentMethod = document.querySelector(
			'input[name="payment-method"]:checked'
		)?.id;

		if (!paymentMethod) {
			showSnackbar("Please select a payment method", "error");
			return false;
		}

		// If credit card is selected, validate card details
		if (paymentMethod === "credit-card") {
			const cardNumber = document
				.getElementById("card-number")
				?.value?.trim();
			const expiry = document.getElementById("expiry")?.value?.trim();
			const cvv = document.getElementById("cvv")?.value?.trim();
			const cardName = document
				.getElementById("card-name")
				?.value?.trim();

			if (!cardNumber) {
				showSnackbar("Please enter your card number", "error");
				document.getElementById("card-number")?.focus();
				return false;
			}

			// Basic card number validation (remove spaces)
			const cleanCardNumber = cardNumber.replace(/\s/g, "");
			if (cleanCardNumber.length < 13 || cleanCardNumber.length > 19) {
				showSnackbar("Please enter a valid card number", "error");
				document.getElementById("card-number")?.focus();
				return false;
			}

			if (!expiry) {
				showSnackbar("Please enter the card expiration date", "error");
				document.getElementById("expiry")?.focus();
				return false;
			}

			// Basic expiry validation (MM/YY format)
			const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
			if (!expiryRegex.test(expiry)) {
				showSnackbar(
					"Please enter expiry date in MM/YY format",
					"error"
				);
				document.getElementById("expiry")?.focus();
				return false;
			}

			if (!cvv) {
				showSnackbar("Please enter the CVV security code", "error");
				document.getElementById("cvv")?.focus();
				return false;
			}

			if (cvv.length < 3 || cvv.length > 4) {
				showSnackbar("Please enter a valid CVV (3-4 digits)", "error");
				document.getElementById("cvv")?.focus();
				return false;
			}

			if (!cardName) {
				showSnackbar("Please enter the name on the card", "error");
				document.getElementById("card-name")?.focus();
				return false;
			}

			// Save payment data (in real app, don't store full card details)
			const newPaymentData = {
				method: "credit-card",
				cardLast4: cleanCardNumber.slice(-4),
				cardName,
			};
			setPaymentData(newPaymentData);
		} else if (paymentMethod === "bank-transfer") {
			// Bank transfer selected - no additional validation needed
			const newPaymentData = {
				method: "bank-transfer",
				displayName: "Bank Transfer",
			};
			setPaymentData(newPaymentData);
		} else if (paymentMethod === "cash-on-delivery") {
			// Cash on delivery selected - no additional validation needed
			const newPaymentData = {
				method: "cash-on-delivery",
				displayName: "Cash on Delivery",
			};
			setPaymentData(newPaymentData);
		} else {
			// For other payment methods
			const newPaymentData = { method: paymentMethod };
			setPaymentData(newPaymentData);
		}

		return true;
	};

	const validateStep4 = () => {
		const termsAccepted = document.getElementById("terms")?.checked;

		if (!termsAccepted) {
			showSnackbar(
				"Please accept the terms and conditions to continue",
				"error"
			);
			document.getElementById("terms")?.focus();
			return false;
		}

		return true;
	};

	// Navigate to specific step
	const goToStep = (stepNumber) => {
		// Validate current step before moving forward
		if (stepNumber > currentStep) {
			let isValid = true;

			// Validate all previous steps
			for (let i = currentStep; i < stepNumber; i++) {
				if (i === 1 && !validateStep1()) {
					isValid = false;
					break;
				} else if (i === 2 && !validateStep2()) {
					isValid = false;
					break;
				} else if (i === 3 && !validateStep3()) {
					isValid = false;
					break;
				}
			}

			if (!isValid) return;
		}

		// Update UI
		setCurrentStep(stepNumber);

		// Update step indicators
		document.querySelectorAll(".step").forEach((step, index) => {
			const stepNum = index + 1;
			if (stepNum < stepNumber) {
				step.classList.add("completed");
				step.classList.remove("active");
			} else if (stepNum === stepNumber) {
				step.classList.add("active");
				step.classList.remove("completed");
			} else {
				step.classList.remove("active", "completed");
			}
		});

		// Show/hide forms
		document.querySelectorAll(".checkout-form").forEach((form, index) => {
			const formNum = index + 1;
			if (formNum === stepNumber) {
				form.classList.add("active");
			} else {
				form.classList.remove("active");
			}
		});

		// Update review section if moving to step 4
		if (stepNumber === 4) {
			updateReviewSection();
		}

		// Scroll to top of checkout section
		document
			.getElementById("checkout")
			?.scrollIntoView({ behavior: "smooth", block: "start" });
	};

	// Update review section with collected data
	const updateReviewSection = () => {
		if (customerData) {
			document.querySelector(".review-name").textContent =
				customerData.name || "";
			document.querySelector(".review-email").textContent =
				customerData.email || "";
			document.querySelector(".review-phone").textContent =
				customerData.phone || "";
		}

		if (shippingData) {
			const shippingContent = document.querySelectorAll(
				".review-section-content"
			)[1];
			if (shippingContent) {
				shippingContent.innerHTML = `
					<p><strong>${shippingData.fullName}</strong></p>
					<p>${shippingData.phoneNumber}</p>
					<p>${shippingData.addressLine1}</p>
					<p>${shippingData.addressLine2 ? shippingData.addressLine2 + ", " : ""}</p>
					<p>${shippingData.city}, ${shippingData.state} ${shippingData.postalCode}</p>
				`;
			}
		}

		if (paymentData) {
			const paymentContent = document.querySelectorAll(
				".review-section-content"
			)[2];
			if (paymentContent) {
				let paymentHTML = "";

				if (paymentData.method === "credit-card") {
					paymentHTML = `
						<p><i className="bi bi-credit-card-2-front me-2"></i> Credit Card ending in ${paymentData.cardLast4}</p>
						<p className="text-muted">Name on card: ${paymentData.cardName}</p>
					`;
				} else if (paymentData.method === "paypal") {
					paymentHTML = `
						<p><i className="bi bi-paypal me-2"></i> PayPal</p>
					`;
				} else if (paymentData.method === "apple-pay") {
					paymentHTML = `
						<p><i className="bi bi-apple me-2"></i> Apple Pay</p>
					`;
				} else if (paymentData.method === "bank-transfer") {
					paymentHTML = `
						<p><i className="bi bi-bank me-2"></i> Bank Transfer</p>
						<p className="text-muted">Please transfer the total amount to our bank account. Your order will be processed once payment is confirmed.</p>
					`;
				} else if (paymentData.method === "cash-on-delivery") {
					paymentHTML = `
						<p><i className="bi bi-cash me-2"></i> Cash on Delivery</p>
						<p className="text-muted">Pay with cash when your order is delivered.</p>
					`;
				} else {
					paymentHTML = `
						<p><i className="bi bi-wallet me-2"></i> ${
							paymentData.displayName || paymentData.method
						}</p>
					`;
				}

				paymentContent.innerHTML = paymentHTML;
			}
		}
	};

	// Handle next button click
	const handleNext = (nextStep) => {
		goToStep(nextStep);
	};

	// Handle previous button click
	const handlePrev = (prevStep) => {
		goToStep(prevStep);
	};

	// Handle edit button click in review section
	const handleEdit = (stepToEdit) => {
		goToStep(stepToEdit);
	};
	// Handle form submission
	const handlePlaceOrder = (e) => {
		e.preventDefault();

		// Prevent duplicate submissions
		if (isSubmitting) {
			return;
		}

		// Validate final step
		if (!validateStep4()) {
			return;
		}

		setIsSubmitting(true);

		// calculate total_quantity from checkoutItems
		const total_quantity = checkoutItems.reduce(
			(sum, item) => sum + item.quantity,
			0
		);

		const orderData = {
			customer: customerData,
			shipping: shippingData,
			payment: paymentData,
			items: checkoutItems,
			totals: { total_quantity, subtotal, shipping, total },
		};
		console.log("Order Data:", orderData);

		// Send API request to place order
		fetch(`${API_URL}cart/place-order`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
			},
			body: JSON.stringify(orderData),
		})
			.then((response) => {
				if (!response.ok) {
					// Try to get error details from response
					return response.json().then((errorData) => {
						throw new Error(
							errorData.message || "Failed to place order"
						);
					});
				}
				return response.json();
			})
			.then((data) => {
				if (data.status === "error") {
					throw new Error(data.message || "Failed to place order");
				}
				// console.log("Order placed successfully:", data);
				showSnackbar(
					"Order placed successfully! Thank you for your purchase.",
					"success"
				);

				// Redirect to order details page after 2 seconds
				setTimeout(() => {
					navigate("/order-details?order_id=" + data.order_id);
				}, 2000);
			})
			.catch((error) => {
				// console.error("Error placing order:", error);
				showSnackbar("Error placing order. Please try again.", "error");
				setIsSubmitting(false); // Re-enable submission on error
			});
	};
	// Setup payment method change listeners (only runs once on mount)
	useEffect(() => {
		// Payment method radio buttons - toggle payment method body visibility
		const handlePaymentMethodChange = (e) => {
			// Hide all payment method bodies
			document
				.querySelectorAll(".payment-method-body")
				.forEach((body) => {
					body.classList.add("d-none");
				});

			// Show selected payment method body
			const selectedMethod = e.target.closest(".payment-method");
			const methodBody = selectedMethod?.querySelector(
				".payment-method-body"
			);
			if (methodBody) {
				methodBody.classList.remove("d-none");
			}

			// Update active class
			document.querySelectorAll(".payment-method").forEach((method) => {
				method.classList.remove("active");
			});
			selectedMethod?.classList.add("active");
		};

		const paymentRadios = document.querySelectorAll(
			'input[name="payment-method"]'
		);
		paymentRadios.forEach((radio) => {
			radio.addEventListener("change", handlePaymentMethodChange);
		});

		// Cleanup
		return () => {
			paymentRadios.forEach((radio) => {
				radio.removeEventListener("change", handlePaymentMethodChange);
			});
		};
	}, []); // Empty dependency array - runs only once

	return (
		<section id="checkout" className="checkout section">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
				<div className="row">
					<div className="col-lg-8">
						{/* Checkout Steps */}
						<div
							className="checkout-steps mb-4 aos-init aos-animate"
							data-aos="fade-up"
						>
							<div className="step active" data-step="1">
								<div className="step-number">1</div>
								<div className="step-title">Information</div>
							</div>
							<div className="step-connector"></div>
							<div className="step" data-step="2">
								<div className="step-number">2</div>
								<div className="step-title">Shipping</div>
							</div>
							<div className="step-connector"></div>
							<div className="step" data-step="3">
								<div className="step-number">3</div>
								<div className="step-title">Payment</div>
							</div>
							<div className="step-connector"></div>
							<div className="step" data-step="4">
								<div className="step-number">4</div>
								<div className="step-title">Review</div>
							</div>
						</div>

						{/* Checkout Forms Container */}
						<div
							className="checkout-forms aos-init aos-animate"
							data-aos="fade-up"
							data-aos-delay="150"
						>
							{/* Step 1: Customer Information */}
							<div className="checkout-form active" data-form="1">
								<div className="form-header">
									<h3>Customer Information</h3>
									<p>Please enter your contact details</p>
								</div>
								<form className="checkout-form-element">
									<div className="form-group mt-3">
										<label htmlFor="name">Name</label>
										<input
											type="text"
											name="name"
											className="form-control"
											id="name"
											placeholder="Your Name"
											required=""
											value={customerData?.name || ""}
											onChange={(e) =>
												setCustomerData({
													...customerData,
													name: e.target.value,
												})
											}
										/>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="email">
											Email Address
										</label>
										<input
											type="email"
											className="form-control"
											name="email"
											id="email"
											placeholder="Your Email"
											required=""
											value={customerData?.email || ""}
											onChange={(e) =>
												setCustomerData({
													...customerData,
													email: e.target.value,
												})
											}
										/>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="phone">
											Phone Number
										</label>
										<input
											type="tel"
											className="form-control"
											name="phone"
											id="phone"
											placeholder="Your Phone Number"
											required=""
											value={customerData?.phone || ""}
											onChange={(e) =>
												setCustomerData({
													...customerData,
													phone: e.target.value,
												})
											}
										/>
									</div>{" "}
									<div className="text-end mt-4">
										<button
											type="button"
											className="btn btn-primary"
											onClick={() => handleNext(2)}
										>
											Continue to Shipping
										</button>
									</div>
								</form>
							</div>
							{/* Step 2: Shipping Address */}
							<div className="checkout-form" data-form="2">
								<div className="form-header">
									<h3>Shipping Address</h3>
									<p>Where should we deliver your order?</p>
								</div>
								<form className="checkout-form-element">
									{addresses && addresses.length > 0 && (
										<div className="form-group mb-3">
											<label htmlFor="saved-address">
												Saved Addresses
											</label>
											{addresses.length === 1 ? (
												<div className="saved-address-display p-3 border rounded">
													<p className="mb-1">
														<strong>
															{
																addresses[0]
																	.full_name
															}
														</strong>
													</p>
													<p className="mb-1">
														{
															addresses[0]
																.phone_number
														}
													</p>
													<p className="mb-1">
														{
															addresses[0]
																.address_line1
														}
													</p>
													{addresses[0]
														.address_line2 && (
														<p className="mb-1">
															{
																addresses[0]
																	.address_line2
															}
														</p>
													)}
													<p className="mb-1">
														{addresses[0].city},{" "}
														{addresses[0].state}{" "}
														{
															addresses[0]
																.postal_code
														}
													</p>
													{addresses[0].label && (
														<span className="badge bg-secondary">
															{addresses[0].label}
														</span>
													)}
												</div>
											) : (
												<select
													className="form-select"
													id="saved-address"
													name="saved-address"
													onChange={(e) => {
														const selectedAddress =
															addresses.find(
																(addr) =>
																	addr.id ==
																	e.target
																		.value
															);
														if (selectedAddress) {
															document.getElementById(
																"full-name"
															).value =
																selectedAddress.full_name ||
																"";
															document.getElementById(
																"phone-number"
															).value =
																selectedAddress.phone_number ||
																"";
															document.getElementById(
																"address-line1"
															).value =
																selectedAddress.address_line1 ||
																"";
															document.getElementById(
																"address-line2"
															).value =
																selectedAddress.address_line2 ||
																"";
															document.getElementById(
																"city"
															).value =
																selectedAddress.city ||
																"";
															document.getElementById(
																"state"
															).value =
																selectedAddress.state ||
																"";
															document.getElementById(
																"postal-code"
															).value =
																selectedAddress.postal_code ||
																"";
															document.getElementById(
																"address-label"
															).value =
																selectedAddress.label ||
																"";
														}
													}}
												>
													<option value="">
														Select a saved address
													</option>
													{addresses.map(
														(addr, index) => (
															<option
																key={
																	addr.id ||
																	index
																}
																value={addr.id}
															>
																{addr.label
																	? `${addr.label}: `
																	: ""}
																{
																	addr.address_line1
																}
																, {addr.city},{" "}
																{addr.state}{" "}
																{
																	addr.postal_code
																}
															</option>
														)
													)}
												</select>
											)}
										</div>
									)}
									<div className="form-group">
										<label htmlFor="full-name">
											Full Name
										</label>
										<input
											type="text"
											className="form-control"
											name="full-name"
											id="full-name"
											placeholder="Full Name"
											required=""
											defaultValue={
												addresses &&
												addresses.length === 1
													? addresses[0].full_name
													: ""
											}
										/>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="phone-number">
											Phone Number
										</label>
										<input
											type="tel"
											className="form-control"
											name="phone-number"
											id="phone-number"
											placeholder="Phone Number"
											required=""
											defaultValue={
												addresses &&
												addresses.length === 1
													? addresses[0].phone_number
													: ""
											}
										/>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="address-line1">
											Address Line 1
										</label>
										<input
											type="text"
											className="form-control"
											name="address-line1"
											id="address-line1"
											placeholder="Street Address"
											required=""
											defaultValue={
												addresses &&
												addresses.length === 1
													? addresses[0].address_line1
													: ""
											}
										/>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="address-line2">
											Address Line 2 (optional)
										</label>
										<input
											type="text"
											className="form-control"
											name="address-line2"
											id="address-line2"
											placeholder="Apartment, Suite, Unit, etc."
											defaultValue={
												addresses &&
												addresses.length === 1
													? addresses[0].address_line2
													: ""
											}
										/>
									</div>
									<div className="row mt-3">
										<div className="col-md-4 form-group">
											<label htmlFor="city">City</label>
											<input
												type="text"
												name="city"
												className="form-control"
												id="city"
												placeholder="City"
												required=""
												defaultValue={
													addresses &&
													addresses.length === 1
														? addresses[0].city
														: ""
												}
											/>
										</div>
										<div className="col-md-4 form-group mt-3 mt-md-0">
											<label htmlFor="state">State</label>
											<input
												type="text"
												name="state"
												className="form-control"
												id="state"
												placeholder="State"
												required=""
												defaultValue={
													addresses &&
													addresses.length === 1
														? addresses[0].state
														: ""
												}
											/>
										</div>
										<div className="col-md-4 form-group mt-3 mt-md-0">
											<label htmlFor="postal-code">
												Postal Code
											</label>
											<input
												type="text"
												name="postal-code"
												className="form-control"
												id="postal-code"
												placeholder="Postal Code"
												required=""
												defaultValue={
													addresses &&
													addresses.length === 1
														? addresses[0]
																.postal_code
														: ""
												}
											/>
										</div>
									</div>
									<div className="form-group mt-3">
										<label htmlFor="address-label">
											Address Label (optional)
										</label>
										<input
											type="text"
											className="form-control"
											name="address-label"
											id="address-label"
											placeholder="e.g., Home, Office"
											defaultValue={
												addresses &&
												addresses.length === 1
													? addresses[0].label
													: ""
											}
										/>
									</div>
									<div className="form-check mt-3">
										<input
											className="form-check-input"
											type="checkbox"
											id="save-address"
											name="save-address"
										/>
										<label
											className="form-check-label"
											htmlFor="save-address"
										>
											Save this address for future orders
										</label>
									</div>
									<div className="form-check mt-2">
										<input
											className="form-check-input"
											type="checkbox"
											id="is-default"
											name="is-default"
										/>
										<label
											className="form-check-label"
											htmlFor="is-default"
										>
											Set as default address
										</label>
									</div>{" "}
									<div className="d-flex justify-content-between mt-4">
										<button
											type="button"
											className="btn btn-outline-secondary"
											onClick={() => handlePrev(1)}
										>
											Back to Information
										</button>
										<button
											type="button"
											className="btn btn-primary"
											onClick={() => handleNext(3)}
										>
											Continue to Payment
										</button>
									</div>
								</form>
							</div>
							{/* Step 3: Payment Method */}
							<div className="checkout-form" data-form="3">
								<div className="form-header">
									<h3>Payment Method</h3>
									<p>Choose how you'd like to pay</p>
								</div>
								<form className="checkout-form-element">
									<div className="payment-methods">
										<div className="payment-method active">
											<div className="payment-method-header">
												<div className="form-check">
													<input
														className="form-check-input"
														type="radio"
														name="payment-method"
														id="bank-transfer"
														defaultChecked
														onChange={() =>
															setPaymentData({
																method: "bank-transfer",
																displayName:
																	"Bank Transfer",
															})
														}
													/>
													<label
														className="form-check-label"
														htmlFor="bank-transfer"
													>
														Bank Transfer
													</label>
												</div>
												<div className="payment-icons">
													<i className="bi bi-bank"></i>
												</div>
											</div>
											<div className="payment-method-body">
												<p className="mb-3">
													Please transfer the total
													amount to one of the
													following bank accounts:
												</p>
												{bankDetails &&
												bankDetails.length > 0 ? (
													<div className="bank-accounts">
														<div className="row">
															{bankDetails.map(
																(
																	bank,
																	index
																) => (
																	<div
																		key={
																			index
																		}
																		className="col-md-6 mb-3"
																	>
																		<div className="bank-account-card p-3 border rounded h-100">
																			<h5 className="mb-2">
																				{
																					bank.bank_name
																				}
																			</h5>
																			<div className="bank-details">
																				<p className="mb-1">
																					<strong>
																						Account
																						Name:
																					</strong>{" "}
																					{
																						bank.account_name
																					}
																				</p>
																				<p className="mb-1">
																					<strong>
																						Account
																						Number:
																					</strong>{" "}
																					{
																						bank.account_number
																					}
																				</p>
																				<p className="mb-1">
																					<strong>
																						Branch:
																					</strong>{" "}
																					{
																						bank.branch
																					}
																				</p>
																				{bank.branch_code && (
																					<p className="mb-1">
																						<strong>
																							Branch
																							Code:
																						</strong>{" "}
																						{
																							bank.branch_code
																						}
																					</p>
																				)}
																				{bank.instructions && (
																					<div className="mt-2">
																						<strong>
																							Instructions:
																						</strong>
																						<p className="mb-0 text-muted">
																							{
																								bank.instructions
																							}
																						</p>
																					</div>
																				)}
																			</div>
																		</div>
																	</div>
																)
															)}
														</div>
													</div>
												) : (
													<p className="text-muted">
														Loading bank details...
													</p>
												)}
												<div className="alert alert-info mt-3">
													<i className="bi bi-info-circle me-2"></i>
													Please include your order
													number as the payment
													reference. Your order will
													be processed once we confirm
													the payment.
												</div>
											</div>
										</div>

										<div className="payment-method mt-3">
											<div className="payment-method-header">
												<div className="form-check">
													<input
														className="form-check-input"
														type="radio"
														name="payment-method"
														id="cash-on-delivery"
														onChange={() =>
															setPaymentData({
																method: "cash-on-delivery",
																displayName:
																	"Cash on Delivery",
															})
														}
													/>
													<label
														className="form-check-label"
														htmlFor="cash-on-delivery"
													>
														Cash on Delivery
													</label>
												</div>
												<div className="payment-icons">
													<i className="bi bi-cash"></i>
												</div>
											</div>
											<div className="payment-method-body d-none">
												<p>
													Pay with cash when your
													order is delivered to your
													doorstep.
												</p>
												<div className="alert alert-warning mt-2">
													<i className="bi bi-exclamation-triangle me-2"></i>
													Please ensure someone is
													available to receive and pay
													for the order at the
													delivery address.
												</div>
											</div>
										</div>
									</div>{" "}
									<div className="d-flex justify-content-between mt-4">
										<button
											type="button"
											className="btn btn-outline-secondary"
											onClick={() => handlePrev(2)}
										>
											Back to Shipping
										</button>
										<button
											type="button"
											className="btn btn-primary"
											onClick={() => handleNext(4)}
										>
											Review Order
										</button>
									</div>
								</form>
							</div>{" "}
							{/* Step 4: Order Review */}
							<div className="checkout-form" data-form="4">
								<div className="form-header">
									<h3>Review Your Order</h3>
									<p>
										Please review your information before
										placing your order
									</p>
								</div>
								<form
									className="checkout-form-element"
									onSubmit={handlePlaceOrder}
								>
									<div className="review-sections">
										<div className="review-section">
											<div className="review-section-header">
												<h4>Contact Information</h4>
												<button
													type="button"
													className="btn-edit"
													onClick={() =>
														handleEdit(1)
													}
												>
													Edit
												</button>
											</div>
											<div className="review-section-content">
												<p className="review-name">
													John Doe
												</p>
												<p className="review-email">
													johndoe@example.com
												</p>
												<p className="review-phone">
													+1 (555) 123-4567
												</p>
											</div>
										</div>

										<div className="review-section mt-3">
											<div className="review-section-header">
												<h4>Shipping Address</h4>
												<button
													type="button"
													className="btn-edit"
													onClick={() =>
														handleEdit(2)
													}
												>
													Edit
												</button>
											</div>
											<div className="review-section-content">
												<p>123 Main Street, Apt 4B</p>
												<p>New York, NY 10001</p>
												<p>United States</p>
											</div>
										</div>

										<div className="review-section mt-3">
											<div className="review-section-header">
												<h4>Payment Method</h4>
												<button
													type="button"
													className="btn-edit"
													onClick={() =>
														handleEdit(3)
													}
												>
													Edit
												</button>
											</div>
											<div className="review-section-content">
												<p>
													<i className="bi bi-credit-card-2-front me-2"></i>{" "}
													Credit Card ending in 3456
												</p>
											</div>
										</div>
									</div>

									<div className="form-check mt-4">
										<input
											className="form-check-input"
											type="checkbox"
											id="terms"
											name="terms"
											required=""
										/>
										<label
											className="form-check-label"
											htmlFor="terms"
										>
											I agree to the{" "}
											<a
												href="#"
												data-bs-toggle="modal"
												data-bs-target="#termsModal"
											>
												Terms and Conditions
											</a>{" "}
											and{" "}
											<a
												href="#"
												data-bs-toggle="modal"
												data-bs-target="#privacyModal"
											>
												Privacy Policy
											</a>
										</label>
									</div>
									<div className="success-message d-none">
										Your order has been placed successfully!
										Thank you for your purchase.
									</div>
									<div className="d-flex justify-content-between mt-4">
										<button
											type="button"
											className="btn btn-outline-secondary"
											onClick={() => handlePrev(3)}
										>
											Back to Payment
										</button>
										<button
											type="submit"
											className="btn btn-success place-order-btn"
											disabled={isSubmitting}
										>
											{isSubmitting
												? "Processing..."
												: "Place Order"}
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div className="col-lg-4">
						{/* Order Summary */}
						<div
							className="order-summary aos-init aos-animate"
							data-aos="fade-left"
							data-aos-delay="200"
						>
							<div className="order-summary-header">
								<h3>Order Summary</h3>
								<button
									type="button"
									className="btn-toggle-summary d-lg-none"
								>
									<i className="bi bi-chevron-down"></i>
								</button>
							</div>

							<div className="order-summary-content">
								<div className="order-items">
									{checkoutItems.map((item, index) => (
										<div className="order-item" key={index}>
											<div className="order-item-image">
												<img
													src={
														BACKEND_URL +
														item.main_image
													}
													alt={item.name}
													className="img-fluid"
												/>
											</div>
											<div className="order-item-details">
												<h4>{item.name}</h4>
												<div className="order-item-price">
													<span className="quantity">
														{item.quantity} ×
													</span>
													<span className="price">
														{formatCurrency(
															item.discount_price >
																0
																? item.discount_price
																: item.item_price
														)}
													</span>
													<span> = </span>
													<span className="price text-end">
														{item?.discount_price >
														0
															? formatCurrency(
																	item.discount_price *
																		item.quantity
															  )
															: formatCurrency(
																	item.item_price *
																		item.quantity
															  )}
													</span>
												</div>
											</div>
										</div>
									))}
								</div>

								<div className="order-totals">
									<div className="order-subtotal d-flex justify-content-between">
										<span>Subtotal</span>
										<span>{formatCurrency(subtotal)}</span>
									</div>
									<div className="order-shipping d-flex justify-content-between">
										<span>Shipping</span>
										<span>{formatCurrency(shipping)}</span>
									</div>
									<div className="order-total d-flex justify-content-between">
										<span>Total</span>
										<span>{formatCurrency(total)}</span>
									</div>
								</div>

								<div className="promo-code mt-3">
									<div className="input-group">
										<input
											type="text"
											className="form-control"
											placeholder="Promo Code"
											aria-label="Promo Code"
										/>
										<button
											className="btn btn-outline-primary"
											type="button"
										>
											Apply
										</button>
									</div>
								</div>

								<div className="secure-checkout mt-4">
									<div className="secure-checkout-header">
										<i className="bi bi-shield-lock"></i>
										<span>Secure Checkout</span>
									</div>
									<div className="payment-icons mt-2">
										<i className="bi bi-credit-card-2-front"></i>
										<i className="bi bi-credit-card"></i>
										<i className="bi bi-paypal"></i>
										<i className="bi bi-apple"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				{/* Terms and Privacy Modals */}
				<div
					className="modal fade"
					id="termsModal"
					tabIndex="-1"
					aria-labelledby="termsModalLabel"
					aria-hidden="true"
				>
					<div className="modal-dialog modal-dialog-scrollable">
						<div className="modal-content">
							<div className="modal-header">
								<h5
									className="modal-title"
									id="termsModalLabel"
								>
									Terms and Conditions
								</h5>
								<button
									type="button"
									className="btn-close"
									data-bs-dismiss="modal"
									aria-label="Close"
								></button>
							</div>
							<div className="modal-body">
								<p>
									Lorem ipsum dolor sit amet, consectetur
									adipiscing elit. Nullam in dui mauris.
									Vivamus hendrerit arcu sed erat molestie
									vehicula. Sed auctor neque eu tellus rhoncus
									ut eleifend nibh porttitor. Ut in nulla
									enim. Phasellus molestie magna non est
									bibendum non venenatis nisl tempor.
								</p>
								<p>
									Suspendisse in orci enim. Vivamus hendrerit
									arcu sed erat molestie vehicula. Sed auctor
									neque eu tellus rhoncus ut eleifend nibh
									porttitor. Ut in nulla enim. Phasellus
									molestie magna non est bibendum non
									venenatis nisl tempor.
								</p>
								<p>
									Suspendisse in orci enim. Vivamus hendrerit
									arcu sed erat molestie vehicula. Sed auctor
									neque eu tellus rhoncus ut eleifend nibh
									porttitor. Ut in nulla enim. Phasellus
									molestie magna non est bibendum non
									venenatis nisl tempor.
								</p>
							</div>
							<div className="modal-footer">
								<button
									type="button"
									className="btn btn-primary"
									data-bs-dismiss="modal"
								>
									I Understand
								</button>
							</div>
						</div>
					</div>
				</div>

				<div
					className="modal fade"
					id="privacyModal"
					tabIndex="-1"
					aria-labelledby="privacyModalLabel"
					aria-hidden="true"
				>
					<div className="modal-dialog modal-dialog-scrollable">
						<div className="modal-content">
							<div className="modal-header">
								<h5
									className="modal-title"
									id="privacyModalLabel"
								>
									Privacy Policy
								</h5>
								<button
									type="button"
									className="btn-close"
									data-bs-dismiss="modal"
									aria-label="Close"
								></button>
							</div>
							<div className="modal-body">
								<p>
									Lorem ipsum dolor sit amet, consectetur
									adipiscing elit. Nullam in dui mauris.
									Vivamus hendrerit arcu sed erat molestie
									vehicula. Sed auctor neque eu tellus rhoncus
									ut eleifend nibh porttitor. Ut in nulla
									enim.
								</p>
								<p>
									Suspendisse in orci enim. Vivamus hendrerit
									arcu sed erat molestie vehicula. Sed auctor
									neque eu tellus rhoncus ut eleifend nibh
									porttitor. Ut in nulla enim. Phasellus
									molestie magna non est bibendum non
									venenatis nisl tempor.
								</p>
								<p>
									Suspendisse in orci enim. Vivamus hendrerit
									arcu sed erat molestie vehicula. Sed auctor
									neque eu tellus rhoncus ut eleifend nibh
									porttitor. Ut in nulla enim. Phasellus
									molestie magna non est bibendum non
									venenatis nisl tempor.
								</p>
							</div>
							<div className="modal-footer">
								<button
									type="button"
									className="btn btn-primary"
									data-bs-dismiss="modal"
								>
									I Understand
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	);
}

export default Checkout;
