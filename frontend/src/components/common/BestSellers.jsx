import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";
import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import { useNavigate } from "react-router-dom";
import { useSnackbar } from "../../context/SnackbarContext";
import { useCart } from "../../context/CartContext";

function BestSellers() {
	const location = useLocation();
	const navigate = useNavigate();
	const [bestSellersProducts, setBestSellersProducts] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const { showSnackbar } = useSnackbar();
	const { refreshCart } = useCart();

	useEffect(() => {
		const fetchBestSellersProducts = async () => {
			try {
				const response = await fetch(
					`${API_URL}getBestSellersProducts`
				);
				const data = await response.json();
				if (data.success) {
					setBestSellersProducts(data.products);
				} else {
					setError(data.message);
				}
			} catch (error) {
				setError("Error fetching best sellers products");
			} finally {
				setLoading(false);
			}
		};

		fetchBestSellersProducts();
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
										Loading best sellers products...
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
			{/* Best Sellers Section */}
			<section id="best-sellers" className="best-sellers section">
				{/* Section Title */}
				<div className="container section-title" data-aos="fade-up">
					<h2>Best Sellers</h2>
					<p>
						Our most popular Batik sarees are selling fast. Grab
						your favorite look before it’s gone.
					</p>
				</div>
				{/* End Section Title */}

				<div
					className="container"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row g-4">
						{bestSellersProducts.map((item, index) => (
							<div
								className="col-6 col-lg-3"
								key={item.product.item_id}
							>
								<div
									className="product-card"
									data-aos="zoom-in"
								>
									<div className="product-image">
										<img
											src={`${BACKEND_URL}${item.product.main_image}`}
											className="main-image img-fluid"
											alt={item.product.name}
										/>
										{item.additional_images.length > 0 && (
											<img
												src={`${BACKEND_URL}${item.additional_images[0]}`}
												className="hover-image img-fluid"
												alt={`${item.product.name} Variant`}
											/>
										)}
										<div className="product-overlay">
											<div className="product-actions">
												<a
													className="action-btn"
													data-bs-toggle="tooltip"
													title="View"
													href={`/product/${item.product.url}`}
												>
													<i className="bi bi-eye"></i>
												</a>

												<button
													type="button"
													className="action-btn"
													data-bs-toggle="tooltip"
													title="Add to Cart"
													onClick={(e) =>
														addToCart(
															item.product
																.item_id,
															e
														)
													}
												>
													<i className="bi bi-cart-plus"></i>
												</button>
											</div>
										</div>
										<div className="product-badge sale">
											{Math.round(
												((item.product?.price -
													item.product
														?.discount_price) /
													item.product?.price) *
													100
											)}
											% Off
										</div>
									</div>
									<div className="product-details">
										<div className="product-category">
											<a
												href={`/shop?category=${item.product.category_slug}`}
											>
												{item.product.category_name}
											</a>
										</div>
										<h4 className="product-title">
											<a
												href={`/product/${item.product.url}`}
											>
												{item.product.name}
											</a>
										</h4>
										<div className="product-meta">
											<div className="product-price">
												{item.product.discount_price &&
												parseFloat(
													item.product.discount_price
												) <
													parseFloat(
														item.product.price
													) ? (
													<>
														{formatCurrency(
															item.product
																.discount_price
														)}
														<span className="original-price">
															{formatCurrency(
																item.product
																	.price
															)}
														</span>
													</>
												) : (
													formatCurrency(
														item.product.price
													)
												)}
											</div>
											<div className="product-rating">
												<i className="bi bi-star-fill"></i>
												4.8{" "}
												<span>
													({item.product.sold_qty})
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						))}
					</div>
				</div>
			</section>
			{/* /Best Sellers Section */}
		</div>
	);
}

export default BestSellers;
