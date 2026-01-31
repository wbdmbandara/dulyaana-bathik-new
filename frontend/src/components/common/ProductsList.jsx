import { API_URL, BACKEND_URL, formatNumber, formatCurrency } from "../../config";
import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import { useNavigate } from "react-router-dom";
import { useSnackbar } from "../../context/SnackbarContext";
import { useCart } from "../../context/CartContext";

function ProductsList() {
	const location = useLocation();
	const navigate = useNavigate();
	const [recentProducts, setRecentProducts] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const { showSnackbar } = useSnackbar();
	const { refreshCart } = useCart();

	useEffect(() => {
		const fetchRecentProducts = async () => {
			try {
				const response = await fetch(`${API_URL}getRecentProducts`);
				const data = await response.json();
				if (data.success) {
					setRecentProducts(data.products);
				} else {
					setError(data.message);
				}
			} catch (error) {
				setError("Error fetching recent products");
			} finally {
				setLoading(false);
			}
		};

		fetchRecentProducts();
	}, []);

	const addToCart = async (itemId, e) => {
		e.preventDefault();

		var customerID = JSON.parse(localStorage.getItem("user"))?.id;
		if (!customerID) {
			navigate("/login?redirect=" + window.location.pathname);
			return;
		}

		const button = e.currentTarget;
		button.disabled = true;

		try {
			const response = await fetch(`${API_URL}cart/add`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${localStorage.getItem(
						"auth_token"
					)}`,
				},
				body: JSON.stringify({
					customer_id: customerID,
					item_id: itemId,
					quantity: 1,
				}),
			});

			if (!response.ok) {
				showSnackbar("Failed to add item to cart", "error");
				throw new Error("Failed to add item to cart");
			}

			const data = await response.json();
			showSnackbar(
				data.message || "Item added to cart successfully",
				"success"
			);
			refreshCart();
		} catch (error) {
			console.error("Error adding item to cart:", error);
			showSnackbar(
				"An error occurred while adding item to cart",
				"error"
			);
		} finally {
			button.disabled = false;
		}
	};

	if (loading) {
		return (
			<div>
				<section id="promo-cards" className="promo-cards section">
					<div
						className="container"
						data-aos="fade-up"
						data-aos-delay="100"
					>
						<div className="row g-4">
							<div className="col-12 text-center">
								<div
									className="spinner-border text-primary"
									role="status"
								>
									<span className="visually-hidden">
										Loading recent products...
									</span>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		);
	}

	return (
		<div>
			{/* Product List Section */}
			<section id="product-list" className="product-list section">
				<div
					className="container isotope-layout"
					data-aos="fade-up"
					data-aos-delay="100"
					data-default-filter="*"
					data-layout="masonry"
					data-sort="original-order"
				>
					<div
						className="container section-title aos-init aos-animate"
						data-aos="fade-up"
					>
						<h2>New Arrivals</h2>
						<p>
							Browse our latest collection of authentic Batik
							sarees, perfect for every occasion.
						</p>
					</div>

					<div
						className="row product-container isotope-container"
						data-aos="fade-up"
						data-aos-delay="200"
					>
						{error && (
							<div className="alert alert-danger">{error}</div>
						)}
						{recentProducts.map((product, index) => (
							<div
								className="col-md-6 col-lg-3 product-item isotope-item filter-clothing"
								key={product.id || index}
							>
								<div className="product-card">
									<div className="product-image">
										<div className="product-badge new">New</div>
										<img
											src={
												product.product.main_image
													? `${BACKEND_URL}${product.product.main_image}`
													: null
											}
											alt={
												product.product.name ||
												"Dulyaana Bathik"
											}
											className="img-fluid main-img"
										/>
										<img
											src={
												product.additional_images &&
												product.additional_images
													.length > 0
													? `${BACKEND_URL}${product.additional_images[0]}`
													: product.product.main_image
													? `${BACKEND_URL}${product.product.main_image}`
													: null
											}
											alt={
												product.product.name ||
												"Dulyaana Bathik"
											}
											className="img-fluid hover-img"
										/>
										<div className="product-overlay">
											<button className="btn btn-cart" onClick={(e) => addToCart(product.product.item_id, e)}>
												<i className="bi bi-cart-plus"></i>{" "}
												Add to Cart
											</button>
											<div className="product-actions">
												<a
													href="#"
													className="action-btn"
												>
													<i className="bi bi-heart"></i>
												</a>
												<a
													href={`/product/${product.product.url}`}
													className="action-btn"
												>
													<i className="bi bi-eye"></i>
												</a>
											</div>
										</div>
									</div>
									<div className="product-info">
										<h5 className="product-title">
											<a
												href={`/product/${product.product.url}`}
											>
												{product.product.name}
											</a>
										</h5>
										<div className="product-price">
											<span className="current-price">
												{formatCurrency(
													product.product
														.discount_price > 0
														? product.product
																.discount_price
														: product.product.price
												)}
											</span>
											{product.product?.discount_price >
												0 && (
												<span className="old-price">
													{formatCurrency(
														product.product?.price
													)}
												</span>
											)}
										</div>
										<div className="product-rating">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-half"></i>
											<span>(24)</span>
										</div>
									</div>
								</div>
							</div>
						))}
					</div>

					<div className="text-center mt-5" data-aos="fade-up">
						<a href="/shop" className="view-all-btn">
							View All Products
							<i className="bi bi-arrow-right"></i>
						</a>
					</div>
				</div>
			</section>
			{/* /Product List Section */}
		</div>
	);
}

export default ProductsList;
