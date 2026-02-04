import React, { useState, useEffect } from "react";
import { API_URL, BACKEND_URL, formatNumber, formatCurrency } from "../../config";
import PageTitle from "./PageTitle";
import { useNavigate } from "react-router-dom";
import { useSnackbar } from "../../context/SnackbarContext";
import { useCart } from "../../context/CartContext";

function ProductDetails({ url }) {
	const [product, setProduct] = useState(null);
	const [additionalImages, setAdditionalImages] = useState([]);
	const [activeImageIndex, setActiveImageIndex] = useState(0);
	const [quantity, setQuantity] = useState(1);
	const [videos, setVideos] = useState([]);
	const [youtubeVideos, setYoutubeVideos] = useState([]);
	const [cartErrors, setCartErrors] = useState(null);
	const [cartSuccess, setCartSuccess] = useState(null);
	const navigate = useNavigate();
	const { showSnackbar } = useSnackbar();
	const { refreshCart } = useCart();

	useEffect(() => {
		// Fetch product details using the URL
		const fetchProductDetails = async () => {
			try {
				var productUrl = url.split("/")[2];
				const response = await fetch(
					`${API_URL}getProductDetails/${productUrl}`
				);
				if (!response.ok) {
					throw new Error("Failed to fetch product details");
				}
				const data = await response.json();
				if (data.success === true) {
					setProduct(data.product);
					setAdditionalImages(data.additional_images || []);
					setVideos(data.videos || []);
					setYoutubeVideos(data.youtube_videos || []);
				} else {
					setProduct(null);
				}
			} catch (error) {
				console.error(error);
			}
		};

		fetchProductDetails();
	}, [url]);

	const addToCart = () => {
		setCartErrors(null);
		setCartSuccess(null);

		if (!product) return;
		var customerID = JSON.parse(localStorage.getItem("user"))?.id;
		if (!customerID) {
			navigate("/login?redirect=" + window.location.pathname);
			return;
		}

		fetch(`${API_URL}cart/add`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
			},
			body: JSON.stringify({
				customer_id: customerID,
				item_id: product.item_id,
				quantity: quantity,
			}),
		})
		.then((response) => {
			if (!response.ok) {
				// setCartErrors("Failed to add item to cart");
				showSnackbar("Failed to add item to cart", "error");
				throw new Error("Failed to add item to cart");
			}
			return response.json();
		})
		.then((data) => {
			// setCartSuccess(data.message || "Item added to cart successfully");
			showSnackbar(data.message || "Item added to cart successfully", "success");
			// Refresh cart in header
			refreshCart();
		})
		.catch((error) => {
			// setCartErrors("An error occurred while adding item to cart");
			showSnackbar("An error occurred while adding item to cart", "error");
			console.error(error);
		});
	};

	const handleQuantityChange = (e) => {
		const value = Math.max(
			1,
			Math.min(product?.quantity || 1, Number(e.target.value))
		);
		setQuantity(value);
	};

	const handleQuantityIncrease = () => {
		if (quantity < (product?.quantity || 1)) {
			setQuantity(quantity + 1);
		}
	};

	const handleQuantityDecrease = () => {
		if (quantity > 1) {
			setQuantity(quantity - 1);
		}
	};

	const changeMainImage = (index) => {
		setActiveImageIndex(index);
	};

	if (!product) {
		return <div>Loading...</div>;
	}

	return (
		<div>
			<title>{`Dulyaana Bathik - ${product.name}`}</title>
			<PageTitle title={product.name} />
			<section id="product-details" className="product-details section">
				<div
					className="container aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row g-5">
						{/* Product Images and Videos Column */}
						<div
							className="col-lg-6 mb-5 mb-lg-0 aos-init aos-animate"
							data-aos="fade-right"
							data-aos-delay="200"
						>
							<div className="product-gallery">
								{/* Vertical Thumbnails */}
								<div className="thumbnails-vertical">
									<div className="thumbnails-container">
										{[
											product.main_image,
											...additionalImages,
											...videos.map((video) => BACKEND_URL + video),
											...youtubeVideos,
										].map((media, index) => (
											<div
												key={index}
												className={`thumbnail-item ${
													index === activeImageIndex ? "active" : ""
												}`}
												onClick={() => changeMainImage(index)}
											>
												{media.includes(".mp4") ? (
													<video
														src={media}
														className="img-fluid"
														style={{ maxHeight: "100px" }}
														muted
													/>
												) : media.includes("youtube.com") || media.includes("youtu.be") ? (
													<div className="youtube-thumbnail">
														<img
															src={`https://img.youtube.com/vi/${media.match(/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/watch\?.+&v=))([\w-]{11})/)?.[1]}/mqdefault.jpg`}
															alt="YouTube video"
															className="img-fluid"
														/>
														<div className="youtube-play-icon">
															<i className="bi bi-youtube"></i>
														</div>
													</div>
												) : (
													<img
														src={BACKEND_URL + media}
														alt={product.name}
														className="img-fluid"
													/>
												)}
											</div>
										))}
									</div>
								</div>

								{/* Main Media */}
								<div className="main-image-wrapper">
									<div className="image-zoom-container">
										{(() => {
											const allMedia = [
												product.main_image,
												...additionalImages,
												...videos.map((video) => BACKEND_URL + video),
												...youtubeVideos,
											];
											const currentMedia = allMedia[activeImageIndex];
											
											if (currentMedia?.includes(".mp4")) {
												return (
													<video
														src={currentMedia}
														autoPlay
														controls
														className="img-fluid main-video"
														style={{ maxHeight: "400px", width: "100%" }}
													/>
												);
											} else if (currentMedia?.includes("youtube.com") || currentMedia?.includes("youtu.be")) {
												const videoId = currentMedia.match(/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/watch\?.+&v=))([\w-]{11})/)?.[1];
												return (
													<iframe
														width="100%"
														height="400"
														src={`https://www.youtube.com/embed/${videoId}?autoplay=1&controls=0&modestbranding=1&showinfo=0&rel=0`}
														title="YouTube video"
														frameBorder="0"
														allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
														allowFullScreen
														className="main-video"
													/>
												);
											} else {
												return (
													<img
														src={
															BACKEND_URL +
															[
																product.main_image,
																...additionalImages,
															][activeImageIndex]
														}
														alt={product.name}
														className="img-fluid main-image"
													/>
												);
											}
										})()}
										<div className="zoom-overlay">
											<i className="bi bi-zoom-in"></i>
										</div>
									</div>
									<div className="image-nav">
										<button
											className="image-nav-btn prev-image"
											onClick={() => {
												const totalMedia = 1 + additionalImages.length + videos.length + youtubeVideos.length;
												setActiveImageIndex((prevIndex) =>
													prevIndex > 0
														? prevIndex - 1
														: totalMedia - 1
												);
											}}
										>
											<i className="bi bi-chevron-left"></i>
										</button>
										<button
											className="image-nav-btn next-image"
											onClick={() => {
												const totalMedia = 1 + additionalImages.length + videos.length + youtubeVideos.length;
												setActiveImageIndex((prevIndex) =>
													prevIndex < totalMedia - 1
														? prevIndex + 1
														: 0
												);
											}}
										>
											<i className="bi bi-chevron-right"></i>
										</button>
									</div>
								</div>
							</div>
						</div>

						{/* Product Info Column */}
						<div
							className="col-lg-6 aos-init aos-animate"
							data-aos="fade-left"
							data-aos-delay="200"
						>
							<div
								className="product-info-wrapper"
								id="product-info-sticky"
							>
								{/* Product Meta */}
								<div className="product-meta">
									<div className="d-flex justify-content-between align-items-center mb-3">
										<span className="product-category">
											<a href={`/shop?category=${product.category_slug}`}>
												{product.category_name}
											</a>
										</span>
										<div className="product-share">
											<button
												className="share-btn"
												aria-label="Share product"
											>
												<i className="bi bi-share"></i>
											</button>
											<div className="share-dropdown">
												<a
													href="#"
													aria-label="Share on Facebook"
												>
													<i className="bi bi-facebook"></i>
												</a>
												<a
													href="#"
													aria-label="Share on Twitter"
												>
													<i className="bi bi-twitter-x"></i>
												</a>
												<a
													href="#"
													aria-label="Share on Pinterest"
												>
													<i className="bi bi-pinterest"></i>
												</a>
												<a
													href="#"
													aria-label="Share via Email"
												>
													<i className="bi bi-envelope"></i>
												</a>
											</div>
										</div>
									</div>

									<h1 className="product-title">
										{product.name}
									</h1>

									<div className="product-rating">
										<div className="stars">
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-fill"></i>
											<i className="bi bi-star-half"></i>
											<span className="rating-value">
												4.5
											</span>
										</div>
										<a
											href="#reviews"
											className="rating-count"
										>
											42 Reviews
										</a>
									</div>
								</div>

								{/* Product Price */}
								<div className="product-price-container">
									<div className="price-wrapper">
										<span className="current-price">
											{formatCurrency(
												product?.discount_price > 0
													? product?.discount_price
													: product?.price
											)}
										</span>
										{product?.discount_price > 0 && (
											<span className="original-price">
												{formatCurrency(product?.price)}
											</span>
										)}
									</div>
									{product?.discount_price > 0 && (
										<span className="discount-badge">
											{Math.round(
												((product?.price -
													product?.discount_price) /
													product?.price) *
													100
											)}
											% Off
										</span>
									)}

									<div className="stock-info">
										{product?.quantity > 0 ? (
											<>
												<i className="bi bi-check-circle-fill"></i>
												<span>In Stock</span>
												<span className="stock-count">
													({product?.quantity} items
													left)
												</span>
											</>
										) : (
											<>
												<i className="bi bi-x-circle-fill out-of-stock"></i>
												<span className="out-of-stock">
													Out of Stock
												</span>
											</>
										)}
									</div>
								</div>

								{/* Product Description */}
								<div className="product-short-description">
									<p>{product.description}</p>
								</div>

								{/* Product Options */}
								<div className="product-options">
									{/* Color Options */}
									<div className="option-group d-none">
										<div className="option-header">
											<h6 className="option-title">
												Color
											</h6>
											<span className="selected-option">
												Black
											</span>
										</div>
										<div className="color-options">
											<div
												className="color-option active"
												data-color="Black"
												style={{
													backgroundColor: "#222222",
												}}
											>
												<i className="bi bi-check"></i>
											</div>
											<div
												className="color-option"
												data-color="Silver"
												style={{
													backgroundColor: "#C0C0C0",
												}}
											>
												<i className="bi bi-check"></i>
											</div>
											<div
												className="color-option"
												data-color="Blue"
												style={{
													backgroundColor: "#1E3A8A",
												}}
											>
												<i className="bi bi-check"></i>
											</div>
											<div
												className="color-option"
												data-color="Rose Gold"
												style={{
													backgroundColor: "#B76E79",
												}}
											>
												<i className="bi bi-check"></i>
											</div>
										</div>
									</div>

									{/* Size Options */}
									<div className="option-group d-none">
										<div className="option-header">
											<h6 className="option-title">
												Size
											</h6>
											<span className="selected-option">
												M
											</span>
										</div>
										<div className="size-options">
											<div
												className="size-option"
												data-size="S"
											>
												S
											</div>
											<div
												className="size-option active"
												data-size="M"
											>
												M
											</div>
											<div
												className="size-option"
												data-size="L"
											>
												L
											</div>
										</div>
									</div>

									{/* Quantity Selector */}
									<div className="option-group">
										<h6 className="option-title">
											Quantity
										</h6>
										<div className="quantity-selector">
											<button className="quantity-btn decrease" onClick={handleQuantityDecrease}>
												<i className="bi bi-dash"></i>
											</button>
											<input
												type="number"
												className="quantity-input"
												value={quantity}
												onChange={handleQuantityChange}
												min="1"
												max={product.quantity}
											/>
											<button className="quantity-btn increase" onClick={handleQuantityIncrease}>
												<i className="bi bi-plus"></i>
											</button>
										</div>
									</div>
								</div>

								{/* Cart Messages */}
								{cartErrors && (
									<div className="alert alert-danger alert-dismissible fade show" role="alert">
										{cartErrors}
									</div>
								)}
								{cartSuccess && (
									<div className="alert alert-success alert-dismissible fade show" role="alert">
										{cartSuccess}
									</div>
								)}

								{/* Action Buttons */}
								<div className="product-actions">
									<button className="btn btn-primary add-to-cart-btn" onClick={addToCart}>
										<i className="bi bi-cart-plus"></i> Add
										to Cart
									</button>
									<button className="btn btn-outline-primary buy-now-btn d-none">
										<i className="bi bi-lightning-fill"></i>{" "}
										Buy Now
									</button>
									<button
										className="btn btn-outline-secondary wishlist-btn"
										aria-label="Add to wishlist"
									>
										<i className="bi bi-heart"></i>
									</button>
								</div>

								{/* Delivery Options */}
								<div className="delivery-options d-none">
									<div className="delivery-option">
										<i className="bi bi-truck"></i>
										<div>
											<h6>Free Shipping</h6>
											<p>On orders over $50</p>
										</div>
									</div>
									<div className="delivery-option">
										<i className="bi bi-arrow-repeat"></i>
										<div>
											<h6>30-Day Returns</h6>
											<p>Hassle-free returns</p>
										</div>
									</div>
									<div className="delivery-option">
										<i className="bi bi-shield-check"></i>
										<div>
											<h6>2-Year Warranty</h6>
											<p>Full coverage</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					{/* Sticky Add to Cart Bar (appears on scroll) */}
					<div className="sticky-add-to-cart">
						<div className="container">
							<div className="sticky-content">
								<div className="product-preview">
									<img
										src="assets/img/product/product-details-1.webp"
										alt="Product"
										className="product-thumbnail"
									/>
									<div className="product-info">
										<h5 className="product-title">
											Lorem Ipsum Wireless Headphones
										</h5>
										<div className="product-price">
											$249.99
										</div>
									</div>
								</div>
								<div className="sticky-actions">
									<div className="quantity-selector">
										<button className="quantity-btn decrease" onClick={handleQuantityDecrease}>
											<i className="bi bi-dash"></i>
										</button>
										<input
											type="number"
											className="quantity-input"
											value={quantity}
											onChange={handleQuantityChange}
											min="1"
											max={product.quantity}
										/>
										<button className="quantity-btn increase" onClick={handleQuantityIncrease}>
											<i className="bi bi-plus"></i>
										</button>
									</div>
									<button className="btn btn-primary add-to-cart-btn">
										<i className="bi bi-cart-plus"></i> Add
										to Cart
									</button>
								</div>
							</div>
						</div>
					</div>

					{/* Product Details Accordion */}
					<div
						className="row mt-5 aos-init aos-animate"
						data-aos="fade-up"
					>
						<div className="col-12">
							<div className="product-details-accordion">
								{/* Description Accordion */}
								<div className="accordion-item">
									<h2 className="accordion-header">
										<button
											className="accordion-button"
											type="button"
											data-bs-toggle="collapse"
											data-bs-target="#description"
											aria-expanded="true"
											aria-controls="description"
										>
											Description
										</button>
									</h2>
									<div
										id="description"
										className="accordion-collapse collapse show"
									>
										<div className="accordion-body">
											<div className="product-description">
												<h4>Overview</h4>
												<p>{product.description}</p>

												{product.set_contents ? (
													<>
														<h4>Set Contents</h4>
														<p>
															{
																product.set_contents
															}
														</p>
													</>
												) : null}
											</div>
										</div>
									</div>
								</div>

								{/* Videos Accordion */}
								{(videos.length > 0 || youtubeVideos.length > 0) && (
									<div className="accordion-item">
										<h2 className="accordion-header">
											<button
												className="accordion-button collapsed"
												type="button"
												data-bs-toggle="collapse"
												data-bs-target="#videos"
												aria-expanded="false"
												aria-controls="videos"
											>
												Videos
											</button>
										</h2>
										<div
											id="videos"
											className="accordion-collapse collapse"
										>
											<div className="accordion-body">
												<div className="product-videos">
													<div className="row g-3">
														{videos.map((video, index) => (
															<div key={index} className="col-md-6 col-lg-4">
																<video
																	controls
																	className="video-player w-100"
																	style={{ height: "auto", maxHeight: "300px" }}
																>
																	<source
																		src={BACKEND_URL + video}
																		type="video/mp4"
																	/>
																	Your browser does not support the
																	video tag.
																</video>
															</div>
														))}
														{youtubeVideos.map((video, index) => {
															const videoId = video.match(/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/watch\?.+&v=))([\w-]{11})/)?.[1];
															return (
																<div key={`youtube-${index}`} className="col-md-6 col-lg-4">
																	<iframe
																		width="100%"
																		height="200"
																		src={`https://www.youtube.com/embed/${videoId}`}
																		title={`YouTube video ${index + 1}`}
																		frameBorder="0"
																		allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
																		allowFullScreen
																	/>
																</div>
															);
														})}
													</div>
												</div>
											</div>
										</div>
									</div>
								)}

								{/* Specifications Accordion */}
								<div className="accordion-item d-none">
									<h2 className="accordion-header">
										<button
											className="accordion-button collapsed"
											type="button"
											data-bs-toggle="collapse"
											data-bs-target="#specifications"
											aria-expanded="false"
											aria-controls="specifications"
										>
											Specifications
										</button>
									</h2>
									<div
										id="specifications"
										className="accordion-collapse collapse"
									>
										<div className="accordion-body">
											<div className="product-specifications">
												<div className="row">
													<div className="col-md-6">
														<div className="specs-group">
															<div className="specs-table">
																<div className="specs-row">
																	<div className="specs-label">
																		Fabric
																	</div>
																	<div className="specs-value">
																		{product.fabric ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Pattern
																	</div>
																	<div className="specs-value">
																		{product.pattern ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Occasion
																	</div>
																	<div className="specs-value">
																		{product.occasion ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Saree
																		Work
																	</div>
																	<div className="specs-value">
																		{product.saree_work ||
																			"-"}
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div className="col-md-6">
														<div className="specs-group">
															<div className="specs-table">
																<div className="specs-row">
																	<div className="specs-label">
																		Saree
																		Length
																	</div>
																	<div className="specs-value">
																		{product.saree_length ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Blouse
																		Length
																	</div>
																	<div className="specs-value">
																		{product.blouse_length ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Weight
																	</div>
																	<div className="specs-value">
																		{product.weight ||
																			"-"}
																	</div>
																</div>
																<div className="specs-row">
																	<div className="specs-label">
																		Wash
																		Care
																	</div>
																	<div className="specs-value">
																		{product.wash_care ||
																			"-"}
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								{/* Reviews Accordion */}
								<div className="accordion-item d-none" id="reviews">
									<h2 className="accordion-header">
										<button
											className="accordion-button collapsed"
											type="button"
											data-bs-toggle="collapse"
											data-bs-target="#reviewsContent"
											aria-expanded="false"
											aria-controls="reviewsContent"
										>
											Customer Reviews (42)
										</button>
									</h2>
									<div
										id="reviewsContent"
										className="accordion-collapse collapse"
									>
										<div className="accordion-body">
											<div className="product-reviews">
												<div className="reviews-summary">
													<div className="row">
														<div className="col-lg-4">
															<div className="overall-rating">
																<div className="rating-number">
																	4.5
																</div>
																<div className="rating-stars">
																	<i className="bi bi-star-fill"></i>
																	<i className="bi bi-star-fill"></i>
																	<i className="bi bi-star-fill"></i>
																	<i className="bi bi-star-fill"></i>
																	<i className="bi bi-star-half"></i>
																</div>
																<div className="rating-count">
																	Based on 42
																	reviews
																</div>
															</div>
														</div>

														<div className="col-lg-8">
															<div className="rating-breakdown">
																<div className="rating-bar">
																	<div className="rating-label">
																		5 stars
																	</div>
																	<div className="progress">
																		<div
																			className="progress-bar"
																			role="progressbar"
																			style={{
																				width: "65%",
																			}}
																			aria-valuenow="65"
																			aria-valuemin="0"
																			aria-valuemax="100"
																		></div>
																	</div>
																	<div className="rating-count">
																		27
																	</div>
																</div>
																<div className="rating-bar">
																	<div className="rating-label">
																		4 stars
																	</div>
																	<div className="progress">
																		<div
																			className="progress-bar"
																			role="progressbar"
																			style={{
																				width: "25%",
																			}}
																			aria-valuenow="25"
																			aria-valuemin="0"
																			aria-valuemax="100"
																		></div>
																	</div>
																	<div className="rating-count">
																		10
																	</div>
																</div>
																<div className="rating-bar">
																	<div className="rating-label">
																		3 stars
																	</div>
																	<div className="progress">
																		<div
																			className="progress-bar"
																			role="progressbar"
																			style={{
																				width: "8%",
																			}}
																			aria-valuenow="8"
																			aria-valuemin="0"
																			aria-valuemax="100"
																		></div>
																	</div>
																	<div className="rating-count">
																		3
																	</div>
																</div>
																<div className="rating-bar">
																	<div className="rating-label">
																		2 stars
																	</div>
																	<div className="progress">
																		<div
																			className="progress-bar"
																			role="progressbar"
																			style={{
																				width: "2%",
																			}}
																			aria-valuenow="2"
																			aria-valuemin="0"
																			aria-valuemax="100"
																		></div>
																	</div>
																	<div className="rating-count">
																		1
																	</div>
																</div>
																<div className="rating-bar">
																	<div className="rating-label">
																		1 star
																	</div>
																	<div className="progress">
																		<div
																			className="progress-bar"
																			role="progressbar"
																			style={{
																				width: "2%",
																			}}
																			aria-valuenow="2"
																			aria-valuemin="0"
																			aria-valuemax="100"
																		></div>
																	</div>
																	<div className="rating-count">
																		1
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div className="reviews-list">
													{/* Review Item */}
													<div className="review-item">
														<div className="review-header">
															<div className="reviewer-info">
																<img
																	src="assets/img/person/person-m-1.webp"
																	alt="Reviewer"
																	className="reviewer-avatar"
																/>
																<div>
																	<h5 className="reviewer-name">
																		John Doe
																	</h5>
																	<div className="review-date">
																		03/15/2024
																	</div>
																</div>
															</div>
															<div className="review-rating">
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
															</div>
														</div>
														<h5 className="review-title">
															Exceptional sound
															quality and comfort
														</h5>
														<div className="review-content">
															<p>
																Lorem ipsum
																dolor sit amet,
																consectetur
																adipiscing elit.
																Vestibulum at
																lacus congue,
																suscipit elit
																nec, tincidunt
																orci. Phasellus
																egestas nisi
																vitae lectus
																imperdiet
																venenatis.
																Suspendisse
																vulputate quam
																diam, et
																consectetur
																augue
																condimentum in.
															</p>
														</div>
													</div>
													{/* End Review Item */}

													{/* Review Item */}
													<div className="review-item">
														<div className="review-header">
															<div className="reviewer-info">
																<img
																	src="assets/img/person/person-f-2.webp"
																	alt="Reviewer"
																	className="reviewer-avatar"
																/>
																<div>
																	<h5 className="reviewer-name">
																		Jane
																		Smith
																	</h5>
																	<div className="review-date">
																		02/28/2024
																	</div>
																</div>
															</div>
															<div className="review-rating">
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star"></i>
															</div>
														</div>
														<h5 className="review-title">
															Great headphones,
															battery could be
															better
														</h5>
														<div className="review-content">
															<p>
																Lorem ipsum
																dolor sit amet,
																consectetur
																adipiscing elit.
																Vestibulum at
																lacus congue,
																suscipit elit
																nec, tincidunt
																orci. Phasellus
																egestas nisi
																vitae lectus
																imperdiet
																venenatis.
															</p>
														</div>
													</div>
													{/* End Review Item */}

													{/* Review Item */}
													<div className="review-item">
														<div className="review-header">
															<div className="reviewer-info">
																<img
																	src="assets/img/person/person-m-3.webp"
																	alt="Reviewer"
																	className="reviewer-avatar"
																/>
																<div>
																	<h5 className="reviewer-name">
																		Michael
																		Johnson
																	</h5>
																	<div className="review-date">
																		02/15/2024
																	</div>
																</div>
															</div>
															<div className="review-rating">
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-fill"></i>
																<i className="bi bi-star-half"></i>
															</div>
														</div>
														<h5 className="review-title">
															Impressive noise
															cancellation
														</h5>
														<div className="review-content">
															<p>
																Lorem ipsum
																dolor sit amet,
																consectetur
																adipiscing elit.
																Vestibulum at
																lacus congue,
																suscipit elit
																nec, tincidunt
																orci. Phasellus
																egestas nisi
																vitae lectus
																imperdiet
																venenatis.
																Suspendisse
																vulputate quam
																diam.
															</p>
														</div>
													</div>
													{/* End Review Item */}
												</div>

												<div className="review-form-container mt-5">
													<h4>Write a Review</h4>
													<form className="review-form">
														<div className="rating-select mb-4">
															<label className="form-label">
																Your Rating
															</label>
															<div className="star-rating">
																<input
																	type="radio"
																	id="star5"
																	name="rating"
																	value="5"
																/>
																<label
																	htmlFor="star5"
																	title="5 stars"
																>
																	<i className="bi bi-star-fill"></i>
																</label>
																<input
																	type="radio"
																	id="star4"
																	name="rating"
																	value="4"
																/>
																<label
																	htmlFor="star4"
																	title="4 stars"
																>
																	<i className="bi bi-star-fill"></i>
																</label>
																<input
																	type="radio"
																	id="star3"
																	name="rating"
																	value="3"
																/>
																<label
																	htmlFor="star3"
																	title="3 stars"
																>
																	<i className="bi bi-star-fill"></i>
																</label>
																<input
																	type="radio"
																	id="star2"
																	name="rating"
																	value="2"
																/>
																<label
																	htmlFor="star2"
																	title="2 stars"
																>
																	<i className="bi bi-star-fill"></i>
																</label>
																<input
																	type="radio"
																	id="star1"
																	name="rating"
																	value="1"
																/>
																<label
																	htmlFor="star1"
																	title="1 star"
																>
																	<i className="bi bi-star-fill"></i>
																</label>
															</div>
														</div>

														<div className="row g-3 mb-3">
															<div className="col-md-6">
																<label
																	htmlFor="review-name"
																	className="form-label"
																>
																	Your Name
																</label>
																<input
																	type="text"
																	className="form-control"
																	id="review-name"
																	required=""
																	spellCheck="false"
																	data-ms-editor="true"
																/>
															</div>
															<div className="col-md-6">
																<label
																	htmlFor="review-email"
																	className="form-label"
																>
																	Your Email
																</label>
																<input
																	type="email"
																	className="form-control"
																	id="review-email"
																	required=""
																/>
															</div>
														</div>

														<div className="mb-3">
															<label
																htmlFor="review-title"
																className="form-label"
															>
																Review Title
															</label>
															<input
																type="text"
																className="form-control"
																id="review-title"
																required=""
																spellCheck="false"
																data-ms-editor="true"
															/>
														</div>

														<div className="mb-4">
															<label
																htmlFor="review-content"
																className="form-label"
															>
																Your Review
															</label>
															<textarea
																className="form-control"
																id="review-content"
																rows="4"
																required=""
																spellCheck="false"
																data-ms-editor="true"
															></textarea>
															<div className="form-text">
																Tell others what
																you think about
																this product. Be
																honest and
																helpful!
															</div>
														</div>

														<div className="loading">
															Loading
														</div>
														<div className="error-message"></div>
														<div className="sent-message">
															Your review has been
															submitted. Thank
															you!
														</div>

														<div className="text-end">
															<button
																type="submit"
																className="btn btn-primary"
															>
																Submit Review
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	);
}

export default ProductDetails;
