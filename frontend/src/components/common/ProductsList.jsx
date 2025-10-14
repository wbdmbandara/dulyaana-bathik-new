import { API_URL, BACKEND_URL, formatNumber, formatCurrency } from "../../config";
import React, { useState, useEffect } from "react";

function ProductsList() {
	const [recentProducts, setRecentProducts] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);

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
								<div className="row">
									<div className="col-12">
										<div
											className="product-filters isotope-filters mb-5 d-flex justify-content-center"
											data-aos="fade-up"
										>
											<ul className="d-flex flex-wrap gap-2 list-unstyled">
												<li
													className="filter-active"
													data-filter="*"
												>
													Recently Added
												</li>
											</ul>
										</div>
									</div>
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
										<div className="col-md-6 col-lg-3 product-item isotope-item filter-clothing" key={product.id || index}>
											<div className="product-card">
												<div className="product-image">
													<span className="badge">Sale</span>
													<img
														src={product.product.main_image ? `${BACKEND_URL}${product.product.main_image}` : null}
														alt={product.product.name || "Dulyaana Bathik"}
														className="img-fluid main-img"
													/>
													<img
														src={
															product.additional_images && product.additional_images.length > 0
																? `${BACKEND_URL}${product.additional_images[0]}`
																: product.product.main_image
																? `${BACKEND_URL}${product.product.main_image}`
																: null
														}
														alt={product.product.name || "Dulyaana Bathik"}
														className="img-fluid hover-img"
													/>
													<div className="product-overlay">
														<a
															href="#"
															className="btn-cart"
														>
															<i className="bi bi-cart-plus"></i>{" "}
															Add to Cart
														</a>
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
														<a href={`/product/${product.product.url}`}>
															{product.product.name}
														</a>
													</h5>
													<div className="product-price">
														<span className="current-price">
															{formatCurrency(product.product.discount_price > 0 ? product.product.discount_price : product.product.price)}
														</span>
														{product.product?.discount_price > 0 && (
															<span className="old-price">
																{formatCurrency(product.product?.price)}
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
