import React, { useState } from "react";

function Cart() {
	// State for cart items
	const [cartItems, setCartItems] = useState([
		{
			id: 1,
			name: "Lorem ipsum dolor sit amet",
			image: "assets/img/product/product-1.webp",
			color: "Black",
			size: "M",
			price: 89.99,
			quantity: 1,
			maxQuantity: 10,
		},
		{
			id: 2,
			name: "Consectetur adipiscing elit",
			image: "assets/img/product/product-3.webp",
			color: "White",
			size: "L",
			price: 64.99,
			originalPrice: 79.99,
			quantity: 2,
			maxQuantity: 10,
		},
		{
			id: 3,
			name: "Sed do eiusmod tempor",
			image: "assets/img/product/product-5.webp",
			color: "Blue",
			size: "S",
			price: 49.99,
			quantity: 1,
			maxQuantity: 10,
		},
	]);

	// Handle quantity increase
	const handleQuantityIncrease = (itemId) => {
		setCartItems((items) =>
			items.map((item) =>
				item.id === itemId && item.quantity < item.maxQuantity
					? { ...item, quantity: item.quantity + 1 }
					: item
			)
		);
	};

	// Handle quantity decrease
	const handleQuantityDecrease = (itemId) => {
		setCartItems((items) =>
			items.map((item) =>
				item.id === itemId && item.quantity > 1
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
				if (item.id === itemId) {
					const validQuantity = Math.max(
						1,
						Math.min(item.maxQuantity, quantity)
					);
					return { ...item, quantity: validQuantity };
				}
				return item;
			})
		);
	};

	// Handle item removal
	const handleRemoveItem = (itemId) => {
		setCartItems((items) => items.filter((item) => item.id !== itemId));
	};

	// Calculate totals
	const subtotal = cartItems.reduce(
		(sum, item) => sum + item.price * item.quantity,
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
								<div key={item.id} className="cart-item">
									<div className="row align-items-center">
										<div className="col-lg-6 col-12 mt-3 mt-lg-0 mb-lg-0 mb-3">
											<div className="product-info d-flex align-items-center">
												<div className="product-image">
													<img
														src={item.image}
														alt={item.name}
														className="img-fluid"
														loading="lazy"
													/>
												</div>
												<div className="product-details">
													<h6 className="product-title">
														{item.name}
													</h6>
													<div className="product-meta">
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
																item.id
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
												<span className="current-price">
													${item.price.toFixed(2)}
												</span>
												{item.originalPrice && (
													<span className="original-price">
														$
														{item.originalPrice.toFixed(
															2
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
															item.id
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
															item.id,
															e.target.value
														)
													}
												/>
												<button
													className="quantity-btn increase"
													onClick={() =>
														handleQuantityIncrease(
															item.id
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
													$
													{(
														item.price *
														item.quantity
													).toFixed(2)}
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
											onClick={() => setCartItems([])}
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
									${subtotal.toFixed(2)}
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
											Standard Delivery - $4.99
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
											Express Delivery - $12.99
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
											Free Shipping (Orders over $300)
										</label>
									</div>
								</div>
							</div>

							<div className="summary-item">
								<span className="summary-label">Tax</span>
								<span className="summary-value">
									${tax.toFixed(2)}
								</span>
							</div>

							<div className="summary-item discount">
								<span className="summary-label">Discount</span>
								<span className="summary-value">-$0.00</span>
							</div>

							<div className="summary-total">
								<span className="summary-label">Total</span>
								<span className="summary-value">
									${total.toFixed(2)}
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
