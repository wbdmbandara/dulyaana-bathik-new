import React from "react";

function HomeSlider() {
	return (
		<div>
			{/* Hero Section */}
			<section className="ecommerce-hero-2 hero section" id="hero">
				<div className="container">
					<div
						className="hero-slider swiper init-swiper"
						data-aos="fade-up"
					>
						{/* 
            Swiper configuration example:
            {
              "loop": true,
              "speed": 800,
              "autoplay": {
                "delay": 5000
              },
              "effect": "fade",
              "fadeEffect": {
                "crossFade": true
              },
              "navigation": {
                "nextEl": ".swiper-button-next",
                "prevEl": ".swiper-button-prev"
              }
            }
          */}
						<div className="swiper-wrapper">
							{/* New Collection Slide */}
							<div className="swiper-slide slide-new">
								<div className="row align-items-center">
									<div
										className="col-lg-6 content-col"
										data-aos="fade-right"
										data-aos-delay="100"
									>
										<div className="slide-content">
											<span className="slide-badge">
												New Arrivals
											</span>
											<h1>
												Discover Our <span>Latest</span>{" "}
												Collection
											</h1>
											<p>
												Lorem ipsum dolor sit amet,
												consectetur adipiscing elit.
												Proin eget tortor risus. Vivamus
												magna justo, lacinia eget
												consectetur sed, convallis at
												tellus.
											</p>
											<div className="slide-cta">
												<a
													href="#"
													className="btn btn-shop"
												>
													Shop New Arrivals{" "}
													<i className="bi bi-arrow-right"></i>
												</a>
											</div>
										</div>
									</div>
									<div
										className="col-lg-6 image-col"
										data-aos="fade-left"
										data-aos-delay="200"
									>
										<div className="product-showcase">
											<div className="product-grid">
												<div
													className="product-item"
													data-aos="fade-up"
													data-aos-delay="300"
												>
													<div className="product-image">
														<img
															src="assets/img/product/product-1.webp"
															alt="New Product 1"
														/>
													</div>
													<div className="product-info">
														<h4>Modern Style</h4>
														<span className="price">
															$79.99
														</span>
													</div>
												</div>
												<div
													className="product-item"
													data-aos="fade-up"
													data-aos-delay="400"
												>
													<div className="product-image">
														<img
															src="assets/img/product/product-2.webp"
															alt="New Product 2"
														/>
													</div>
													<div className="product-info">
														<h4>
															Casual Collection
														</h4>
														<span className="price">
															$64.99
														</span>
													</div>
												</div>
												<div
													className="product-item"
													data-aos="fade-up"
													data-aos-delay="500"
												>
													<div className="product-image">
														<img
															src="assets/img/product/product-6.webp"
															alt="New Product 3"
														/>
													</div>
													<div className="product-info">
														<h4>Premium Design</h4>
														<span className="price">
															$89.99
														</span>
													</div>
												</div>
												<div
													className="product-item"
													data-aos="fade-up"
													data-aos-delay="600"
												>
													<div className="product-image">
														<img
															src="assets/img/product/product-7.webp"
															alt="New Product 4"
														/>
													</div>
													<div className="product-info">
														<h4>Elegant Series</h4>
														<span className="price">
															$74.99
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							{/* Sale Products Slide */}
							<div className="swiper-slide slide-sale">
								<div className="row align-items-center">
									<div
										className="col-lg-6 content-col"
										data-aos="fade-right"
										data-aos-delay="100"
									>
										<div className="slide-content">
											<span className="slide-badge">
												Limited Time
											</span>
											<h1>
												Season <span>Sale</span> Up To
												50% Off
											</h1>
											<p>
												Curabitur aliquet quam id dui
												posuere blandit. Nulla quis
												lorem ut libero malesuada
												feugiat. Mauris blandit aliquet
												elit, eget tincidunt nibh
												pulvinar.
											</p>
											<div className="slide-cta">
												<a
													href="#"
													className="btn btn-shop"
												>
													Shop Sale{" "}
													<i className="bi bi-arrow-right"></i>
												</a>
											</div>
											<div className="countdown-container">
												<div className="countdown-label">
													Offer ends in:
												</div>
												<div
													className="countdown d-flex"
													data-count="2025/6/15"
												>
													<div>
														<h3 className="count-days"></h3>
														<h4>Days</h4>
													</div>
													<div>
														<h3 className="count-hours"></h3>
														<h4>Hours</h4>
													</div>
													<div>
														<h3 className="count-minutes"></h3>
														<h4>Minutes</h4>
													</div>
													<div>
														<h3 className="count-seconds"></h3>
														<h4>Seconds</h4>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div
										className="col-lg-6 image-col"
										data-aos="fade-left"
										data-aos-delay="200"
									>
										<div className="sale-showcase">
											<div className="main-product">
												<img
													src="assets/img/product/product-8.webp"
													alt="Sale Product"
												/>
												<div className="discount-badge">
													<span className="percent">
														50%
													</span>
													<span className="text">
														OFF
													</span>
												</div>
											</div>
											<div
												className="floating-tag"
												data-aos="zoom-in"
												data-aos-delay="300"
											>
												<div className="tag-content">
													<span className="tag-label">
														Best Seller
													</span>
													<span className="tag-price">
														<span className="old-price">
															$129.99
														</span>
														<span className="new-price">
															$64.99
														</span>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							{/* Featured Products Slide */}
							<div className="swiper-slide slide-featured">
								<div className="row align-items-center">
									<div
										className="col-lg-6 content-col"
										data-aos="fade-right"
										data-aos-delay="100"
									>
										<div className="slide-content">
											<span className="slide-badge">
												Featured Collection
											</span>
											<h1>
												Premium <span>Quality</span>{" "}
												Products
											</h1>
											<p>
												Vestibulum ac diam sit amet quam
												vehicula elementum sed sit amet
												dui. Donec rutrum congue leo
												eget malesuada. Cras ultricies
												ligula sed magna dictum porta.
											</p>
											<div className="slide-cta">
												<a
													href="#"
													className="btn btn-shop"
												>
													Explore Collection{" "}
													<i className="bi bi-arrow-right"></i>
												</a>
											</div>
											<div className="feature-list">
												<div className="feature-item">
													<i className="bi bi-check-circle-fill"></i>
													<span>
														Premium Materials
													</span>
												</div>
												<div className="feature-item">
													<i className="bi bi-check-circle-fill"></i>
													<span>
														Handcrafted Quality
													</span>
												</div>
												<div className="feature-item">
													<i className="bi bi-check-circle-fill"></i>
													<span>
														Lifetime Warranty
													</span>
												</div>
											</div>
										</div>
									</div>
									<div
										className="col-lg-6 image-col"
										data-aos="fade-left"
										data-aos-delay="200"
									>
										<div className="featured-showcase">
											<div className="featured-image">
												<img
													src="assets/img/product/product-9.webp"
													alt="Featured Product"
												/>
												<div className="featured-badge">
													<i className="bi bi-star-fill"></i>
													<span>Featured</span>
												</div>
											</div>
											<div
												className="floating-review"
												data-aos="fade-up"
												data-aos-delay="300"
											>
												<div className="review-stars">
													<i className="bi bi-star-fill"></i>
													<i className="bi bi-star-fill"></i>
													<i className="bi bi-star-fill"></i>
													<i className="bi bi-star-fill"></i>
													<i className="bi bi-star-fill"></i>
												</div>
												<div className="review-text">
													"Exceptional quality and
													design"
												</div>
												<div className="review-author">
													- Satisfied Customer
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div className="swiper-button-prev"></div>
						<div className="swiper-button-next"></div>
					</div>
				</div>
			</section>
			{/* /Hero Section */}
		</div>
	);
}

export default HomeSlider;
