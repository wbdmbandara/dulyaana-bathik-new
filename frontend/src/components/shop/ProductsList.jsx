import React from 'react'

function ProductsList() {
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
						{/* Product 1 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<span className="product-label">
										New Season
									</span>
									<img
										src="assets/img/product/product-3.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
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
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Vestibulum ante ipsum primis
											</a>
										</h3>
										<div className="product-price">
											<span>$149.99</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star"></i>
										</div>
										<span className="rating-number">4.0</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option"
											style={{ backgroundColor: '#3b82f6' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#22c55e' }}
										></span>
										<span
											className="color-option active"
											style={{ backgroundColor: '#f97316' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 1 */}

						{/* Product 2 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<span className="product-label product-label-sale">
										-30%
									</span>
									<img
										src="assets/img/product/product-6.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
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
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Aliquam tincidunt mauris eu
												risus
											</a>
										</h3>
										<div className="product-price">
											<span className="original">
												$199.99
											</span>
											<span className="sale">$139.99</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-half"></i>
										</div>
										<span className="rating-number">4.5</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option"
											style={{ backgroundColor: '#0ea5e9' }}
										></span>
										<span
											className="color-option active"
											style={{ backgroundColor: '#111827' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#a855f7' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 2 */}

						{/* Product 3 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<img
										src="assets/img/product/product-9.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
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
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Cras ornare tristique elit
											</a>
										</h3>
										<div className="product-price">
											<span>$89.50</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star"></i>
											<i className="bi bi-star"></i>
										</div>
										<span className="rating-number">3.0</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option active"
											style={{ backgroundColor: '#ef4444' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#64748b' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#eab308' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 3 */}

						{/* Product 4 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<img
										src="assets/img/product/product-11.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
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
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Integer vitae libero ac risus
											</a>
										</h3>
										<div className="product-price">
											<span>$119.00</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
										</div>
										<span className="rating-number">5.0</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option"
											style={{ backgroundColor: '#10b981' }}
										></span>
										<span
											className="color-option active"
											style={{ backgroundColor: '#8b5cf6' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#ec4899' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 4 */}

						{/* Product 5 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<span className="product-label product-label-sold">
										Sold Out
									</span>
									<img
										src="assets/img/product/product-2.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
										</div>
										<div className="add-to-cart-container">
											<button
												type="button"
												className="add-to-cart-btn disabled"
											>
												Sold Out
											</button>
										</div>
									</div>
								</div>
								<div className="product-content">
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Donec eu libero sit amet quam
											</a>
										</h3>
										<div className="product-price">
											<span>$75.00</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-half"></i>
										</div>
										<span className="rating-number">4.7</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option active"
											style={{ backgroundColor: '#4b5563' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#e11d48' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#4f46e5' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 5 */}

						{/* Product 6 */}
						<div className="col-lg-6">
							<div className="product-box">
								<div className="product-thumb">
									<span className="product-label product-label-hot">
										Hot
									</span>
									<img
										src="assets/img/product/product-12.webp"
										alt="Product Image"
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
											<button
												type="button"
												className="quick-action-btn"
											>
												<i className="bi bi-eye"></i>
											</button>
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
									<div className="product-details">
										<h3 className="product-title">
											<a href="product-details.html">
												Pellentesque habitant morbi
												tristique
											</a>
										</h3>
										<div className="product-price">
											<span>$64.95</span>
										</div>
									</div>
									<div className="product-rating-container">
										<div className="rating-stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-half"></i>
											<i className="bi bi-star"></i>
										</div>
										<span className="rating-number">3.6</span>
									</div>
									<div className="product-color-options">
										<span
											className="color-option"
											style={{ backgroundColor: '#eab308' }}
										></span>
										<span
											className="color-option"
											style={{ backgroundColor: '#14b8a6' }}
										></span>
										<span
											className="color-option active"
											style={{ backgroundColor: '#facc15' }}
										></span>
									</div>
								</div>
							</div>
						</div>
						{/* End Product 6 */}
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
									<span className="d-none d-sm-inline">Next</span>
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

export default ProductsList