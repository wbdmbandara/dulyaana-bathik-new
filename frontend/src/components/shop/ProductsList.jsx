import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";
import React, { useState, useEffect } from "react";

function ProductsList() {
	const [products, setProducts] = useState([]);
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchProducts = async () => {
			try {
				const currentUrl = window.location.href;
				const urlParams = new URLSearchParams(currentUrl.split("?")[1]);

				const category = urlParams.get("category") || "";
				const search = urlParams.get("search") || "";
				const min_price = urlParams.get("min_price") || "";
				const max_price = urlParams.get("max_price") || "";
				const sort = urlParams.get("sort") || "featured";
				const fabrics = urlParams.get("fabrics") || "";
				const limit = urlParams.get("limit") || "12";
				urlParams.set("category", category);
				urlParams.set("search", search);
				urlParams.set("min_price", min_price);
				urlParams.set("max_price", max_price);
				urlParams.set("sort", sort);
				urlParams.set("fabrics", fabrics);
				urlParams.set("limit", limit);

				const response = await fetch(
					`${API_URL}getItems?${urlParams.toString()}`
				);
				const data = await response.json();
				setProducts(data.data);
			} catch (error) {
				console.error("Error fetching products:", error);
			} finally {
				setLoading(false);
			}
		};

		fetchProducts();
	}, []);

	return (
		<div>
			<section
				id="category-product-list"
				className="category-product-list section"
			>
				<div
					className="container aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row gy-4">
						{/* display products */}
						{loading ? (
							<p>Loading products...</p>
						) : products.length > 0 ? (
							products.map((product, index) => (
								<div
									key={
										product.product.id || `product-${index}`
									}
									className="col-lg-6 col-md-4 col-6"
								>
									<div className="product-box">
										<div className="product-thumb">
											{product.product?.discount_price >
												0 && (
												<span className="product-label product-label-sale">
													{Math.round(
														((product.product
															?.price -
															product.product
																?.discount_price) /
															product.product
																?.price) *
															100
													)}
													% Off
												</span>
											)}
											<img
												src={
													BACKEND_URL +
													product.product.main_image
												}
												alt={product.product.name}
												className="main-img"
												loading="lazy"
											/>
											<div className="product-overlay">
												<div className="product-quick-actions">
													<button
														type="button"
														className="quick-action-btn"
													>
														<i className="bi bi-heart"></i>
													</button>
													<button
														type="button"
														className="quick-action-btn"
													>
														<i className="bi bi-arrow-repeat"></i>
													</button>
													<a
														href={
															"/product/" +
																product.product
																	?.url || "#"
														}
														className="quick-action-btn"
													>
														<i className="bi bi-eye"></i>
													</a>
												</div>
												<div className="add-to-cart-container">
													<button
														type="button"
														className="add-to-cart-btn"
													>
														Add to Cart
													</button>
												</div>
											</div>
										</div>
										<div className="product-content">
											<div className="row">
												<div className="col-6">
													<a href={`/shop?category=${product.product?.category_slug}`} className="prod-category">{product.product?.category_name || "Uncategorized"}</a>
												</div>
												<div className="col-6">
													<div className="product-rating-container">
														<div className="rating-stars">
															<i className="bi bi-star-fill"></i>
															<i className="bi bi-star-fill"></i>
															<i className="bi bi-star-fill"></i>
															<i className="bi bi-star-fill"></i>
															<i className="bi bi-star"></i>
														</div>
														<span className="rating-number">
															4.0
														</span>
													</div>
												</div>
											</div>
											<div className="product-details">
												<h3 className="product-title">
													<a
														href={
															"/product/" +
																product.product
																	?.url || "#"
														}
													>
														{product.product.name}
													</a>
												</h3>
												{product.product
													?.discount_price > 0 && (
													<div className="product-price">
														<span className="original">
															{formatCurrency(
																product.product
																	?.price
															)}
														</span>

														<span className="sale">
															{formatCurrency(
																product.product
																	?.discount_price
															)}
														</span>
													</div>
												)}
												{product.product
													?.discount_price ===
													null && (
													<div className="product-price">
														<span>
															{formatCurrency(
																product.product
																	?.price
															)}
														</span>
													</div>
												)}
											</div>
										</div>
									</div>
								</div>
							))
						) : (
							<p>No products found.</p>
						)}
					</div>
				</div>
			</section>

			<section
				id="category-pagination"
				className="category-pagination section"
			>
				<div className="container">
					<nav
						className="d-flex justify-content-center"
						aria-label="Page navigation"
					>
						<ul>
							<li>
								<a href="#" aria-label="Previous page">
									<i className="bi bi-arrow-left"></i>
									<span className="d-none d-sm-inline">
										Previous
									</span>
								</a>
							</li>

							<li>
								<a href="#" className="active">
									1
								</a>
							</li>
							<li>
								<a href="#">2</a>
							</li>
							<li>
								<a href="#">3</a>
							</li>
							<li className="ellipsis">...</li>
							<li>
								<a href="#">8</a>
							</li>
							<li>
								<a href="#">9</a>
							</li>
							<li>
								<a href="#">10</a>
							</li>

							<li>
								<a href="#" aria-label="Next page">
									<span className="d-none d-sm-inline">
										Next
									</span>
									<i className="bi bi-arrow-right"></i>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</section>
		</div>
	);
}

export default ProductsList;
