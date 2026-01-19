import React, { useState, useEffect, use } from "react";
import { API_URL, BACKEND_URL, formatNumber, formatCurrency } from "../../config";
import { useNavigate } from "react-router-dom";

function Cart() {
	const navigate = useNavigate();
	// State for cart items
	const [cartItems, setCartItems] = useState([]);

	useEffect(() => {
		const storedUser = localStorage.getItem("user");
		if (!storedUser) {
			navigate("/login?redirect=/cart");
		}
	}, []);

	var customerID = JSON.parse(localStorage.getItem("user"))?.id;

	// Get cart items 
	useEffect(() => {
		const fetchCartItems = async () => {
			try {
				fetch(`${API_URL}cart`, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
					},
					body: JSON.stringify({
						customer_id: customerID,
					}),
				}).then((response) => {
					if (!response.ok) {
						throw new Error("Failed to fetch cart items");
					}

					return response.json();
				}).then((data) => {
					// console.log(data);
					setCartItems(data.cart_items);
				}).catch((error) => {
					console.error(error);
				});
			} catch (error) {
				console.error("Error fetching cart items:", error);
			}
		};
		fetchCartItems();
	}, []);

	// Handle quantity increase
	const handleQuantityIncrease = (itemId) => {
		setCartItems((items) =>
			items.map((item) =>
				item.item_id === itemId && item.quantity < item.available_qty
					? { ...item, quantity: item.quantity + 1 }
					: item
			)
		);
	};

	// Handle quantity decrease
	const handleQuantityDecrease = (itemId) => {
		setCartItems((items) =>
			items.map((item) =>
				item.item_id === itemId && item.quantity > 1
					? { ...item, quantity: item.quantity - 1 }
					: item
			)
		);
	};

	// Handle manual quantity input change
	const handleQuantityChange = (itemId, newQuantity) => {
		const quantity = parseInt(newQuantity);
		if (isNaN(quantity)) return;

		setCartItems((items) =>
			items.map((item) => {
				if (item.item_id === itemId) {
					const validQuantity = Math.max(
						1,
						Math.min(item.available_qty, quantity)
					);
					return { ...item, quantity: validQuantity };
				}
				return item;
			})
		);
	};

	// Handle item removal
	const handleRemoveItem = (itemId) => {
		setCartItems((items) => items.filter((item) => item.item_id !== itemId));
		removeCartItem(itemId);
	};

	const removeCartItem = async (itemId) => {
		try {
			const response = await fetch(`${API_URL}cart/remove`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
				},
				body: JSON.stringify({
					customer_id: customerID,
					item_id: itemId,
				}),
			});
			if (!response.ok) {
				throw new Error("Failed to remove cart item");
			}
		} catch (error) {
			console.error("Error removing cart item:", error);
		}
	};

	useEffect(() => {
		const updateCart = async () => {
			try {
				const response = await fetch(`${API_URL}cart/update`, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
					},
					body: JSON.stringify({
						customer_id: customerID,
						cart_items: cartItems,
					}),
				});
				if (!response.ok) {
					throw new Error("Failed to update cart");
				}
			} catch (error) {
				console.error("Error updating cart:", error);
			}
		};
		updateCart();
	}, [cartItems]);

	// Handle clear cart
	const clearCart = () => {
		setCartItems([]);
		const clearCartOnServer = async () => {
			try {
				const response = await fetch(`${API_URL}cart/clear`, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
					},
					body: JSON.stringify({
						customer_id: customerID,
					}),
				});
				if (!response.ok) {
					throw new Error("Failed to clear cart");
				}
			} catch (error) {
				console.error("Error clearing cart:", error);
			}
		};
		clearCartOnServer();
	};

	// Calculate totals
	const subtotal = cartItems.reduce(
		(sum, item) =>
			sum +
			(item?.discount_price > 0
				? item?.discount_price
				: item?.item_price) * item.quantity,
		0
	);
	const tax = subtotal * 0.1; // 10% tax
	const shipping = 4.99; // Default shipping
	const total = subtotal + tax + shipping;

	return (
		<section id="cart" className="cart section">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
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

							{cartItems.map((item) => (
								<div key={item.item_id} className="cart-item">
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
															Color: {item.color}
														</span>
														<span className="product-size">
															Size: {item.size}
														</span>
													</div>
													<button
														className="remove-item"
														type="button"
														onClick={() =>
															handleRemoveItem(
																item.item_id
															)
														}
													>
														<i className="bi bi-trash"></i>{" "}
														Remove
													</button>
												</div>
											</div>
										</div>
										<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
											<div className="price-tag">
												<span className="current-price d-block">
													{formatCurrency(
														item?.discount_price > 0
															? item?.discount_price
															: item?.item_price
													)}
												</span>
												{item.discount_price > 0 && (
													<span className="original-price d-block">
														{formatCurrency(
															item.item_price
														)}
													</span>
												)}
											</div>
										</div>
										<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
											<div className="quantity-selector">
												<button
													className="quantity-btn decrease"
													onClick={() =>
														handleQuantityDecrease(
															item.item_id
														)
													}
												>
													<i className="bi bi-dash"></i>
												</button>
												<input
													type="number"
													className="quantity-input"
													value={item.quantity}
													min="1"
													max={item.maxQuantity}
													onChange={(e) =>
														handleQuantityChange(
															item.item_id,
															e.target.value
														)
													}
												/>
												<button
													className="quantity-btn increase"
													onClick={() =>
														handleQuantityIncrease(
															item.item_id
														)
													}
												>
													<i className="bi bi-plus"></i>
												</button>
											</div>
										</div>
										<div className="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
											<div className="item-total">
												<span>
													{
														item?.discount_price > 0 
														? formatCurrency(item.discount_price * item.quantity) 
														: formatCurrency(item.item_price * item.quantity)
													}
												</span>
											</div>
										</div>
									</div>
								</div>
							))}

							<div className="cart-actions">
								<div className="row">
									<div className="col-lg-6 mb-3 mb-lg-0">
										<div className="coupon-form">
											<div className="input-group">
												<input
													type="text"
													className="form-control"
													placeholder="Coupon code"
												/>
												<button
													className="btn btn-outline-accent"
													type="button"
												>
													Apply Coupon
												</button>
											</div>
										</div>
									</div>
									<div className="col-lg-6 text-md-end">
										<button className="btn btn-outline-heading me-2">
											<i className="bi bi-arrow-clockwise"></i>{" "}
											Update Cart
										</button>
										<button
											className="btn btn-outline-remove"
											onClick={() => clearCart()}
										>
											<i className="bi bi-trash"></i>{" "}
											Clear Cart
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div
						className="col-lg-4 mt-4 mt-lg-0 aos-init aos-animate"
						data-aos="fade-up"
						data-aos-delay="300"
					>
						<div className="cart-summary">
							<h4 className="summary-title">Order Summary</h4>

							<div className="summary-item">
								<span className="summary-label">Subtotal</span>
								<span className="summary-value">
									{formatCurrency(subtotal)}
								</span>
							</div>

							<div className="summary-item shipping-item">
								<span className="summary-label">Shipping</span>
								<div className="shipping-options">
									<div className="form-check text-end">
										<input
											className="form-check-input"
											type="radio"
											name="shipping"
											id="standard"
											defaultChecked
										/>
										<label
											className="form-check-label"
											htmlFor="standard"
										>
											Standard Delivery - {formatCurrency(4.99)}
										</label>
									</div>
									<div className="form-check text-end">
										<input
											className="form-check-input"
											type="radio"
											name="shipping"
											id="express"
										/>
										<label
											className="form-check-label"
											htmlFor="express"
										>
											Express Delivery - {formatCurrency(12.99)}
										</label>
									</div>
									<div className="form-check text-end">
										<input
											className="form-check-input"
											type="radio"
											name="shipping"
											id="free"
										/>
										<label
											className="form-check-label"
											htmlFor="free"
										>
											Free Shipping (Orders over {formatCurrency(300)})
										</label>
									</div>
								</div>
							</div>

							<div className="summary-item">
								<span className="summary-label">Tax</span>
								<span className="summary-value">
									{formatCurrency(tax)}
								</span>
							</div>

							<div className="summary-item discount">
								<span className="summary-label">Discount</span>
								<span className="summary-value">-{formatCurrency(0)}</span>
							</div>

							<div className="summary-total">
								<span className="summary-label">Total</span>
								<span className="summary-value">
									{formatCurrency(total)}
								</span>
							</div>

							<div className="checkout-button">
								<a href="#" className="btn btn-accent w-100">
									Proceed to Checkout{" "}
									<i className="bi bi-arrow-right"></i>
								</a>
							</div>

							<div className="continue-shopping">
								<a href="/shop" className="btn btn-link w-100">
									<i className="bi bi-arrow-left"></i>{" "}
									Continue Shopping
								</a>
							</div>

							<div className="payment-methods">
								<p className="payment-title">We Accept</p>
								<div className="payment-icons">
									<i className="bi bi-credit-card"></i>
									{/* <i className="bi bi-paypal"></i> */}
									<i className="bi bi-wallet2"></i>
									<i className="bi bi-bank"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	);
}

export default Cart;
