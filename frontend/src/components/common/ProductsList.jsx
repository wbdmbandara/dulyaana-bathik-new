import React from "react";

function ProductsList() {
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
										All
									</li>
									<li data-filter=".filter-clothing">
										Clothing
									</li>
									<li data-filter=".filter-accessories">
										Accessories
									</li>
									<li data-filter=".filter-electronics">
										Electronics
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
						{/* Product Item 1 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-clothing">
							<div className="product-card">
								<div className="product-image">
									<span className="badge">Sale</span>
									<img
										src="assets/img/product/product-1.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-1-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Lorem ipsum dolor sit amet
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$89.99
										</span>
										<span className="old-price">
											$129.99
										</span>
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
						{/* End Product Item */}

						{/* Product Item 2 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-electronics">
							<div className="product-card">
								<div className="product-image">
									<img
										src="assets/img/product/product-2.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-2-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Consectetur adipiscing elit
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$249.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star"></i>
										<span>(18)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 3 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-accessories">
							<div className="product-card">
								<div className="product-image">
									<span className="badge">New</span>
									<img
										src="assets/img/product/product-3.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-3-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Sed do eiusmod tempor
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$59.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star"></i>
										<i className="bi bi-star"></i>
										<span>(7)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 4 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-clothing">
							<div className="product-card">
								<div className="product-image">
									<img
										src="assets/img/product/product-4.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-4-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Incididunt ut labore et dolore
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$79.99
										</span>
										<span className="old-price">
											$99.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<span>(32)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 5 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-electronics">
							<div className="product-card">
								<div className="product-image">
									<span className="badge">Sale</span>
									<img
										src="assets/img/product/product-5.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-5-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Magna aliqua ut enim ad minim
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$199.99
										</span>
										<span className="old-price">
											$249.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-half"></i>
										<i className="bi bi-star"></i>
										<span>(15)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 6 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-accessories">
							<div className="product-card">
								<div className="product-image">
									<img
										src="assets/img/product/product-6.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-6-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Veniam quis nostrud exercitation
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$45.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star"></i>
										<span>(21)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 7 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-clothing">
							<div className="product-card">
								<div className="product-image">
									<span className="badge">New</span>
									<img
										src="assets/img/product/product-7.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-7-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Ullamco laboris nisi ut aliquip
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$69.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-half"></i>
										<i className="bi bi-star"></i>
										<span>(11)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}

						{/* Product Item 8 */}
						<div className="col-md-6 col-lg-3 product-item isotope-item filter-electronics">
							<div className="product-card">
								<div className="product-image">
									<img
										src="assets/img/product/product-8.webp"
										alt="Product"
										className="img-fluid main-img"
									/>
									<img
										src="assets/img/product/product-8-variant.webp"
										alt="Product Hover"
										className="img-fluid hover-img"
									/>
									<div className="product-overlay">
										<a
											href="cart.html"
											className="btn-cart"
										>
											<i className="bi bi-cart-plus"></i>{" "}
											Add to Cart
										</a>
										<div className="product-actions">
											<a href="#" className="action-btn">
												<i className="bi bi-heart"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-eye"></i>
											</a>
											<a href="#" className="action-btn">
												<i className="bi bi-arrow-left-right"></i>
											</a>
										</div>
									</div>
								</div>
								<div className="product-info">
									<h5 className="product-title">
										<a href="product-details.html">
											Ex ea commodo consequat
										</a>
									</h5>
									<div className="product-price">
										<span className="current-price">
											$159.99
										</span>
									</div>
									<div className="product-rating">
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<i className="bi bi-star-fill"></i>
										<span>(29)</span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product Item */}
					</div>

					<div className="text-center mt-5" data-aos="fade-up">
						<a href="category.html" className="view-all-btn">
							View All Products{" "}
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
