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

function ProductsList() {
	const location = useLocation();
	const [products, setProducts] = useState([]);
	const [loading, setLoading] = useState(true);
	const [pagination, setPagination] = useState({
		total: 0,
		last_page: 1,
		current_page: 1,
	});
	const { showSnackbar } = useSnackbar();
	const { refreshCart } = useCart();
	const navigate = useNavigate();
	useEffect(() => {
		const fetchProducts = async () => {
			setLoading(true);
			try {
				const urlParams = new URLSearchParams(location.search);

				const category = urlParams.get("category") || "";
				const search = urlParams.get("search") || "";
				const min_price = urlParams.get("min_price") || "";
				const max_price = urlParams.get("max_price") || "";
				const sort = urlParams.get("sort") || "featured";
				const fabrics = urlParams.get("fabrics") || "";
				const limit = urlParams.get("limit") || "12";
				const page = urlParams.get("page") || "1";

				const params = new URLSearchParams();
				params.set("category", category);
				params.set("search", search);
				params.set("min_price", min_price);
				params.set("max_price", max_price);
				params.set("sort", sort);
				params.set("fabrics", fabrics);
				params.set("limit", limit);
				params.set("page", page);

				const response = await fetch(
					`${API_URL}getItems?${params.toString()}`
				);
				const data = await response.json();
				setProducts(data.data);
				setPagination(data.pagination);
			} catch (error) {
				console.error("Error fetching products:", error);
			} finally {
				setLoading(false);
			}
		};

		fetchProducts();
	}, [location.search]);

	const addToCart = (itemId) => async (e) => {
		e.preventDefault();

		var customerID = JSON.parse(localStorage.getItem("user"))?.id;
		if (!customerID) {
			navigate("/login?redirect=" + window.location.pathname);
			return;
		}

		const button = e.currentTarget;
		const originalText = button.textContent;
		button.textContent = "Updating...";
		button.disabled = true;

		try {
			await fetch(`${API_URL}cart/add`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
				},
				body: JSON.stringify({
					customer_id: customerID,
					item_id: itemId,
					quantity: 1,
				}),
			})
			.then((response) => {
				if (!response.ok) {
					showSnackbar("Failed to add item to cart", "error");
					throw new Error("Failed to add item to cart");
				}
				return response.json();
			})
			.then((data) => {
				showSnackbar(data.message || "Item added to cart successfully", "success");
				refreshCart();
			})
			.catch((error) => {
				showSnackbar("An error occurred while adding item to cart", "error");
				console.error(error);
			});
		} catch (error) {
			console.error("Error adding item to cart:", error);
			showSnackbar("An error occurred while adding item to cart", "error");
		} finally {
			button.textContent = originalText;
			button.disabled = false;
		}
	};

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
									className="col-lg-6 col-md-6 col-12"
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
														onClick={addToCart(
															product.product
																?.item_id
														)}
													>
														Add to Cart
													</button>
												</div>
											</div>
										</div>
										<div className="product-content">
											<div className="row">
												<div className="col-6">
													<a
														href={`/shop?category=${product.product?.category_slug}`}
														className="prod-category"
													>
														{product.product
															?.category_name ||
															"Uncategorized"}
													</a>
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
							{/* pagination */}
							{pagination.total >= 1 && (
								<>
									<li>
										<a
											href="#"
											aria-label="Previous page"
											onClick={(e) => {
												e.preventDefault();
												if (
													pagination.current_page > 1
												) {
													const urlParams =
														new URLSearchParams(
															window.location.search
														);
													urlParams.set(
														"page",
														pagination.current_page -
															1
													);
													window.location.search =
														urlParams.toString();
												}
											}}
										>
											<i className="bi bi-arrow-left"></i>
											<span className="d-none d-sm-inline">
												Previous
											</span>
										</a>
									</li>
									{pagination.total >= 6 &&
									pagination.last_page > 1 ? (
										<>
											<li>
												<a
													href="#"
													onClick={(e) => {
														e.preventDefault();
														const urlParams =
															new URLSearchParams(
																window.location.search
															);
														urlParams.set(
															"page",
															1
														);
														window.location.search =
															urlParams.toString();
													}}
													className={`page-item ${
														pagination.current_page ===
														1
															? "active"
															: ""
													}`}
												>
													1
												</a>
											</li>
											{pagination.current_page > 3 && (
												<li className="ellipsis">
													...
												</li>
											)}
											{Array.from(
												{ length: 3 },
												(_, index) => {
													const page =
														Math.max(
															2,
															pagination.current_page -
																1
														) + index;
													return (
														page <
															pagination.last_page && (
															<li key={page}>
																<a
																	href="#"
																	onClick={(
																		e
																	) => {
																		e.preventDefault();
																		const urlParams =
																			new URLSearchParams(
																				window.location.search
																			);
																		urlParams.set(
																			"page",
																			page
																		);
																		window.location.search =
																			urlParams.toString();
																	}}
																	className={`page-item ${
																		pagination.current_page ===
																		page
																			? "active"
																			: ""
																	}`}
																>
																	{page}
																</a>
															</li>
														)
													);
												}
											)}
											{pagination.current_page <
												pagination.last_page - 2 && (
												<li className="ellipsis">
													...
												</li>
											)}
											<li>
												<a
													href="#"
													onClick={(e) => {
														e.preventDefault();
														const urlParams =
															new URLSearchParams(
																window.location.search
															);
														urlParams.set(
															"page",
															pagination.last_page
														);
														window.location.search =
															urlParams.toString();
													}}
													className={`page-item ${
														pagination.current_page ===
														pagination.last_page
															? "active"
															: ""
													}`}
												>
													{pagination.last_page}
												</a>
											</li>
										</>
									) : (
										Array.from(
											{ length: pagination.last_page },
											(_, index) => (
												<li key={index + 1}>
													<a
														href="#"
														onClick={(e) => {
															e.preventDefault();
															const urlParams =
																new URLSearchParams(
																	window.location.search
																);
															urlParams.set(
																"page",
																index + 1
															);
															window.location.search =
																urlParams.toString();
														}}
														className={`page-item ${
															pagination.current_page ===
															index + 1
																? "active"
																: ""
														}`}
													>
														{index + 1}
													</a>
												</li>
											)
										)
									)}
									<li>
										<a
											href="#"
											aria-label="Next page"
											onClick={(e) => {
												e.preventDefault();
												if (
													pagination.current_page <
													pagination.last_page
												) {
													const urlParams =
														new URLSearchParams(
															window.location.search
														);
													urlParams.set(
														"page",
														pagination.current_page +
															1
													);
													window.location.search =
														urlParams.toString();
												}
											}}
										>
											<span className="d-none d-sm-inline">
												Next
											</span>
											<i className="bi bi-arrow-right"></i>
										</a>
									</li>
								</>
							)}
						</ul>
					</nav>
				</div>
			</section>
		</div>
	);
}

export default ProductsList;
