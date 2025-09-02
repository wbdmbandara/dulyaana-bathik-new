import React from "react";
import { Link } from "react-router-dom";

function Header() {
	return (
		<header id="header" className="header position-relative">
			{/* Top Bar */}
			<div className="top-bar py-2 d-none d-lg-block">
				<div className="container-fluid container-xl">
					<div className="row align-items-center">
						<div className="col-lg-6">
							<div className="d-flex align-items-center">
								<div className="top-bar-item me-4">
									<i className="bi bi-telephone-fill me-2"></i>
									<span>Customer Support: </span>
									<a href="tel:+1234567890">
										+1 (234) 567-890
									</a>
								</div>
								<div className="top-bar-item">
									<i className="bi bi-envelope-fill me-2"></i>
									<a href="#">
										<span
											className="__cf_email__"
											data-cfemail="3a494f4a4a55484e7a5f425b574a565f14595557"
										>
											[email&#160;protected]
										</span>
									</a>
								</div>
							</div>
						</div>

						<div className="col-lg-6">
							<div className="d-flex justify-content-end">
								<div className="top-bar-item me-4">
									<a href="track-order.html">
										<i className="bi bi-truck me-2"></i>
										Track Order
									</a>
								</div>
								<div className="top-bar-item dropdown me-4">
									<a
										href="#"
										className="dropdown-toggle"
										data-bs-toggle="dropdown"
									>
										<i className="bi bi-translate me-2"></i>
										English
									</a>
									<ul className="dropdown-menu">
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												<i className="bi bi-check2 me-2 selected-icon"></i>
												English
											</a>
										</li>
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												Español
											</a>
										</li>
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												Français
											</a>
										</li>
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												Deutsch
											</a>
										</li>
									</ul>
								</div>
								<div className="top-bar-item dropdown">
									<a
										href="#"
										className="dropdown-toggle"
										data-bs-toggle="dropdown"
									>
										<i className="bi bi-currency-dollar me-2"></i>
										USD
									</a>
									<ul className="dropdown-menu">
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												<i className="bi bi-check2 me-2 selected-icon"></i>
												USD
											</a>
										</li>
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												EUR
											</a>
										</li>
										<li>
											<a
												className="dropdown-item"
												href="#"
											>
												GBP
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{/*Main Header */}
			<div className="main-header">
				<div className="container-fluid container-xl">
					<div className="d-flex py-3 align-items-center justify-content-between">
						{/*Logo */}
						<a
							href="index.html"
							className="logo d-flex align-items-center"
						>
							{/*Uncomment the line below if you also wish to use an image logo */}
							{/*<img src="assets/img/logo.webp" alt=""> */}
							<h1 className="sitename">
								Fashion<span>Store</span>
							</h1>
						</a>

						{/*Search */}
						<form className="search-form desktop-search-form">
							<div className="input-group">
								<input
									type="text"
									className="form-control"
									placeholder="Search for products..."
								/>
								<button
									className="btn search-btn"
									type="submit"
								>
									<i className="bi bi-search"></i>
								</button>
							</div>
						</form>

						{/*Actions */}
						<div className="header-actions d-flex align-items-center justify-content-end">
							{/*Mobile Search Toggle */}
							<button
								className="header-action-btn mobile-search-toggle d-xl-none"
								type="button"
								data-bs-toggle="collapse"
								data-bs-target="#mobileSearch"
								aria-expanded="false"
								aria-controls="mobileSearch"
							>
								<i className="bi bi-search"></i>
							</button>

							{/*Account */}
							<div className="dropdown account-dropdown">
								<button
									className="header-action-btn"
									data-bs-toggle="dropdown"
								>
									<i className="bi bi-person"></i>
									<span className="action-text d-none d-md-inline-block">
										Account
									</span>
								</button>
								<div className="dropdown-menu">
									<div className="dropdown-header">
										<h6>
											Welcome to{" "}
											<span className="sitename">
												FashionStore
											</span>
										</h6>
										<p className="mb-0">
											Access account &amp; manage orders
										</p>
									</div>
									<div className="dropdown-body">
										<a
											className="dropdown-item d-flex align-items-center"
											href="account.html"
										>
											<i className="bi bi-person-circle me-2"></i>
											<span>My Profile</span>
										</a>
										<a
											className="dropdown-item d-flex align-items-center"
											href="orders.html"
										>
											<i className="bi bi-bag-check me-2"></i>
											<span>My Orders</span>
										</a>
										<a
											className="dropdown-item d-flex align-items-center"
											href="wishlist.html"
										>
											<i className="bi bi-heart me-2"></i>
											<span>My Wishlist</span>
										</a>
										<a
											className="dropdown-item d-flex align-items-center"
											href="returns.html"
										>
											<i className="bi bi-arrow-return-left me-2"></i>
											<span>Returns &amp; Refunds</span>
										</a>
										<a
											className="dropdown-item d-flex align-items-center"
											href="settings.html"
										>
											<i className="bi bi-gear me-2"></i>
											<span>Settings</span>
										</a>
									</div>
									<div className="dropdown-footer">
										<a
											href="login.html"
											className="btn btn-primary w-100 mb-2"
										>
											Sign In
										</a>
										<a
											href="register.html"
											className="btn btn-outline-primary w-100"
										>
											Register
										</a>
									</div>
								</div>
							</div>

							{/*Wishlist */}
							<a
								href="wishlist.html"
								className="header-action-btn d-none d-md-flex"
							>
								<i className="bi bi-heart"></i>
								<span className="action-text d-none d-md-inline-block">
									Wishlist
								</span>
								<span className="badge">0</span>
							</a>

							{/*Cart */}
							<div className="dropdown cart-dropdown">
								<button
									className="header-action-btn"
									data-bs-toggle="dropdown"
								>
									<i className="bi bi-cart3"></i>
									<span className="action-text d-none d-md-inline-block">
										Cart
									</span>
									<span className="badge">3</span>
								</button>
								<div className="dropdown-menu cart-dropdown-menu">
									<div className="dropdown-header">
										<h6>Shopping Cart (3)</h6>
									</div>
									<div className="dropdown-body">
										<div className="cart-items">
											{/*Cart Item 1 */}
											<div className="cart-item">
												<div className="cart-item-image">
													<img
														src="assets/img/product/product-1.webp"
														alt="Product"
														className="img-fluid"
													/>
												</div>
												<div className="cart-item-content">
													<h6 className="cart-item-title">
														Wireless Headphones
													</h6>
													<div className="cart-item-meta">
														1 × $89.99
													</div>
												</div>
												<button className="cart-item-remove">
													<i className="bi bi-x"></i>
												</button>
											</div>

											{/*Cart Item 2 */}
											<div className="cart-item">
												<div className="cart-item-image">
													<img
														src="assets/img/product/product-2.webp"
														alt="Product"
														className="img-fluid"
													/>
												</div>
												<div className="cart-item-content">
													<h6 className="cart-item-title">
														Smart Watch
													</h6>
													<div className="cart-item-meta">
														1 × $129.99
													</div>
												</div>
												<button className="cart-item-remove">
													<i className="bi bi-x"></i>
												</button>
											</div>

											{/*Cart Item 3 */}
											<div className="cart-item">
												<div className="cart-item-image">
													<img
														src="assets/img/product/product-3.webp"
														alt="Product"
														className="img-fluid"
													/>
												</div>
												<div className="cart-item-content">
													<h6 className="cart-item-title">
														Bluetooth Speaker
													</h6>
													<div className="cart-item-meta">
														1 × $59.99
													</div>
												</div>
												<button className="cart-item-remove">
													<i className="bi bi-x"></i>
												</button>
											</div>
										</div>
									</div>
									<div className="dropdown-footer">
										<div className="cart-total">
											<span>Total:</span>
											<span className="cart-total-price">
												$279.97
											</span>
										</div>
										<div className="cart-actions">
											<a
												href="cart.html"
												className="btn btn-outline-primary"
											>
												View Cart
											</a>
											<a
												href="checkout.html"
												className="btn btn-primary"
											>
												Checkout
											</a>
										</div>
									</div>
								</div>
							</div>

							{/*Mobile Navigation Toggle */}
							<i className="mobile-nav-toggle d-xl-none bi bi-list me-0"></i>
						</div>
					</div>
				</div>
			</div>

			{/*Navigation */}
			<div className="header-nav">
				<div className="container-fluid container-xl position-relative">
					<nav id="navmenu" className="navmenu">
						<ul>
							<li>
								<a href="index.html" className="active">
									Home
								</a>
							</li>
							<li>
								<a href="about.html">About</a>
							</li>
							<li>
								<a href="category.html">Category</a>
							</li>
							<li>
								<a href="product-details.html">
									Product Details
								</a>
							</li>
							<li>
								<a href="cart.html">Cart</a>
							</li>
							<li>
								<a href="checkout.html">Checkout</a>
							</li>
							<li className="dropdown">
								<a href="#">
									<span>Dropdown</span>{" "}
									<i className="bi bi-chevron-down toggle-dropdown"></i>
								</a>
								<ul>
									<li>
										<a href="#">Dropdown 1</a>
									</li>
									<li className="dropdown">
										<a href="#">
											<span>Deep Dropdown</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Deep Dropdown 1</a>
											</li>
											<li>
												<a href="#">Deep Dropdown 2</a>
											</li>
											<li>
												<a href="#">Deep Dropdown 3</a>
											</li>
											<li>
												<a href="#">Deep Dropdown 4</a>
											</li>
											<li>
												<a href="#">Deep Dropdown 5</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#">Dropdown 2</a>
									</li>
									<li>
										<a href="#">Dropdown 3</a>
									</li>
									<li>
										<a href="#">Dropdown 4</a>
									</li>
								</ul>
							</li>

							{/*Products Mega Menu 1 */}
							<li className="products-megamenu-1">
								<a href="#">
									<span>Megamenu 1</span>{" "}
									<i className="bi bi-chevron-down toggle-dropdown"></i>
								</a>

								{/*Products Mega Menu 1 Mobile View */}
								<ul className="mobile-megamenu">
									<li>
										<a href="#">Featured Products</a>
									</li>
									<li>
										<a href="#">New Arrivals</a>
									</li>
									<li>
										<a href="#">Sale Items</a>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Clothing</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Men's Wear</a>
											</li>
											<li>
												<a href="#">Women's Wear</a>
											</li>
											<li>
												<a href="#">Kids Collection</a>
											</li>
											<li>
												<a href="#">Sportswear</a>
											</li>
											<li>
												<a href="#">Accessories</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Electronics</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Smartphones</a>
											</li>
											<li>
												<a href="#">Laptops</a>
											</li>
											<li>
												<a href="#">Audio Devices</a>
											</li>
											<li>
												<a href="#">Smart Home</a>
											</li>
											<li>
												<a href="#">Accessories</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Home &amp; Living</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Furniture</a>
											</li>
											<li>
												<a href="#">Decor</a>
											</li>
											<li>
												<a href="#">Kitchen</a>
											</li>
											<li>
												<a href="#">Bedding</a>
											</li>
											<li>
												<a href="#">Lighting</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Beauty</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Skincare</a>
											</li>
											<li>
												<a href="#">Makeup</a>
											</li>
											<li>
												<a href="#">Haircare</a>
											</li>
											<li>
												<a href="#">Fragrances</a>
											</li>
											<li>
												<a href="#">Personal Care</a>
											</li>
										</ul>
									</li>
								</ul>
								{/*End Products Mega Menu 1 Mobile View */}

								{/*Products Mega Menu 1 Desktop View */}
								<div className="desktop-megamenu">
									<div className="megamenu-tabs">
										<ul
											className="nav nav-tabs"
											id="productMegaMenuTabs"
											role="tablist"
										>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link active"
													id="featured-tab"
													data-bs-toggle="tab"
													data-bs-target="#featured-content-1862"
													type="button"
													aria-selected="true"
													role="tab"
												>
													Featured
												</button>
											</li>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link"
													id="new-tab"
													data-bs-toggle="tab"
													data-bs-target="#new-content-1862"
													type="button"
													aria-selected="false"
													tabIndex="-1"
													role="tab"
												>
													New Arrivals
												</button>
											</li>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link"
													id="sale-tab"
													data-bs-toggle="tab"
													data-bs-target="#sale-content-1862"
													type="button"
													aria-selected="false"
													tabIndex="-1"
													role="tab"
												>
													Sale
												</button>
											</li>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link"
													id="category-tab"
													data-bs-toggle="tab"
													data-bs-target="#category-content-1862"
													type="button"
													aria-selected="false"
													tabIndex="-1"
													role="tab"
												>
													Categories
												</button>
											</li>
										</ul>
									</div>

									{/*Tabs Content */}
									<div className="megamenu-content tab-content">
										{/*Featured Tab */}
										<div
											className="tab-pane fade show active"
											id="featured-content-1862"
											role="tabpanel"
											aria-labelledby="featured-tab"
										>
											<div className="product-grid">
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-1.webp"
															alt="Featured Product"
															loading="lazy"
														/>
													</div>
													<div className="product-info">
														<h5>
															Premium Headphones
														</h5>
														<p className="price">
															$129.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-2.webp"
															alt="Featured Product"
															loading="lazy"
														/>
													</div>
													<div className="product-info">
														<h5>Smart Watch</h5>
														<p className="price">
															$199.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-3.webp"
															alt="Featured Product"
															loading="lazy"
														/>
													</div>
													<div className="product-info">
														<h5>
															Wireless Earbuds
														</h5>
														<p className="price">
															$89.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-4.webp"
															alt="Featured Product"
															loading="lazy"
														/>
													</div>
													<div className="product-info">
														<h5>
															Bluetooth Speaker
														</h5>
														<p className="price">
															$79.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
											</div>
										</div>

										{/*New Arrivals Tab */}
										<div
											className="tab-pane fade"
											id="new-content-1862"
											role="tabpanel"
											aria-labelledby="new-tab"
										>
											<div className="product-grid">
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-5.webp"
															alt="New Arrival"
															loading="lazy"
														/>
														<span className="badge-new">
															New
														</span>
													</div>
													<div className="product-info">
														<h5>Fitness Tracker</h5>
														<p className="price">
															$69.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-6.webp"
															alt="New Arrival"
															loading="lazy"
														/>
														<span className="badge-new">
															New
														</span>
													</div>
													<div className="product-info">
														<h5>
															Wireless Charger
														</h5>
														<p className="price">
															$39.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-7.webp"
															alt="New Arrival"
															loading="lazy"
														/>
														<span className="badge-new">
															New
														</span>
													</div>
													<div className="product-info">
														<h5>Smart Bulb Set</h5>
														<p className="price">
															$49.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-8.webp"
															alt="New Arrival"
															loading="lazy"
														/>
														<span className="badge-new">
															New
														</span>
													</div>
													<div className="product-info">
														<h5>
															Portable Power Bank
														</h5>
														<p className="price">
															$59.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
											</div>
										</div>

										{/*Sale Tab */}
										<div
											className="tab-pane fade"
											id="sale-content-1862"
											role="tabpanel"
											aria-labelledby="sale-tab"
										>
											<div className="product-grid">
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-9.webp"
															alt="Sale Product"
															loading="lazy"
														/>
														<span className="badge-sale">
															-30%
														</span>
													</div>
													<div className="product-info">
														<h5>
															Wireless Keyboard
														</h5>
														<p className="price">
															<span className="original-price">
																$89.99
															</span>{" "}
															$62.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-10.webp"
															alt="Sale Product"
															loading="lazy"
														/>
														<span className="badge-sale">
															-25%
														</span>
													</div>
													<div className="product-info">
														<h5>Gaming Mouse</h5>
														<p className="price">
															<span className="original-price">
																$59.99
															</span>{" "}
															$44.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-11.webp"
															alt="Sale Product"
															loading="lazy"
														/>
														<span className="badge-sale">
															-40%
														</span>
													</div>
													<div className="product-info">
														<h5>Desk Lamp</h5>
														<p className="price">
															<span className="original-price">
																$49.99
															</span>{" "}
															$29.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
												<div className="product-card">
													<div className="product-image">
														<img
															src="assets/img/product/product-12.webp"
															alt="Sale Product"
															loading="lazy"
														/>
														<span className="badge-sale">
															-20%
														</span>
													</div>
													<div className="product-info">
														<h5>USB-C Hub</h5>
														<p className="price">
															<span className="original-price">
																$39.99
															</span>{" "}
															$31.99
														</p>
														<a
															href="#"
															className="btn-view"
														>
															View Product
														</a>
													</div>
												</div>
											</div>
										</div>

										{/*Categories Tab */}
										<div
											className="tab-pane fade"
											id="category-content-1862"
											role="tabpanel"
											aria-labelledby="category-tab"
										>
											<div className="category-grid">
												<div className="category-column">
													<h4>Clothing</h4>
													<ul>
														<li>
															<a href="#">
																Men's Wear
															</a>
														</li>
														<li>
															<a href="#">
																Women's Wear
															</a>
														</li>
														<li>
															<a href="#">
																Kids Collection
															</a>
														</li>
														<li>
															<a href="#">
																Sportswear
															</a>
														</li>
														<li>
															<a href="#">
																Accessories
															</a>
														</li>
													</ul>
												</div>
												<div className="category-column">
													<h4>Electronics</h4>
													<ul>
														<li>
															<a href="#">
																Smartphones
															</a>
														</li>
														<li>
															<a href="#">
																Laptops
															</a>
														</li>
														<li>
															<a href="#">
																Audio Devices
															</a>
														</li>
														<li>
															<a href="#">
																Smart Home
															</a>
														</li>
														<li>
															<a href="#">
																Accessories
															</a>
														</li>
													</ul>
												</div>
												<div className="category-column">
													<h4>Home &amp; Living</h4>
													<ul>
														<li>
															<a href="#">
																Furniture
															</a>
														</li>
														<li>
															<a href="#">
																Decor
															</a>
														</li>
														<li>
															<a href="#">
																Kitchen
															</a>
														</li>
														<li>
															<a href="#">
																Bedding
															</a>
														</li>
														<li>
															<a href="#">
																Lighting
															</a>
														</li>
													</ul>
												</div>
												<div className="category-column">
													<h4>Beauty</h4>
													<ul>
														<li>
															<a href="#">
																Skincare
															</a>
														</li>
														<li>
															<a href="#">
																Makeup
															</a>
														</li>
														<li>
															<a href="#">
																Haircare
															</a>
														</li>
														<li>
															<a href="#">
																Fragrances
															</a>
														</li>
														<li>
															<a href="#">
																Personal Care
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								{/*End Products Mega Menu 1 Desktop View */}
							</li>
							{/*End Products Mega Menu 1 */}
							{/*Products Mega Menu 2 */}
							<li className="products-megamenu-2">
								<a href="#">
									<span>Megamenu 2</span>{" "}
									<i className="bi bi-chevron-down toggle-dropdown"></i>
								</a>

								{/*Products Mega Menu 2 Mobile View */}
								<ul className="mobile-megamenu">
									<li>
										<a href="#">Women</a>
									</li>
									<li>
										<a href="#">Men</a>
									</li>
									<li>
										<a href="#">Kids'</a>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Clothing</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">
													Shirts &amp; Tops
												</a>
											</li>
											<li>
												<a href="#">
													Coats &amp; Outerwear
												</a>
											</li>
											<li>
												<a href="#">Underwear</a>
											</li>
											<li>
												<a href="#">Sweatshirts</a>
											</li>
											<li>
												<a href="#">Dresses</a>
											</li>
											<li>
												<a href="#">Swimwear</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Shoes</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Boots</a>
											</li>
											<li>
												<a href="#">Sandals</a>
											</li>
											<li>
												<a href="#">Heels</a>
											</li>
											<li>
												<a href="#">Loafers</a>
											</li>
											<li>
												<a href="#">Slippers</a>
											</li>
											<li>
												<a href="#">Oxfords</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Accessories</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Handbags</a>
											</li>
											<li>
												<a href="#">Eyewear</a>
											</li>
											<li>
												<a href="#">Hats</a>
											</li>
											<li>
												<a href="#">Watches</a>
											</li>
											<li>
												<a href="#">Jewelry</a>
											</li>
											<li>
												<a href="#">Belts</a>
											</li>
										</ul>
									</li>

									<li className="dropdown">
										<a href="#">
											<span>Specialty Sizes</span>{" "}
											<i className="bi bi-chevron-down toggle-dropdown"></i>
										</a>
										<ul>
											<li>
												<a href="#">Plus Size</a>
											</li>
											<li>
												<a href="#">Petite</a>
											</li>
											<li>
												<a href="#">Wide Shoes</a>
											</li>
											<li>
												<a href="#">Narrow Shoes</a>
											</li>
										</ul>
									</li>
								</ul>
								{/*End Products Mega Menu 2 Mobile View */}

								{/*Products Mega Menu 2 Desktop View */}
								<div className="desktop-megamenu">
									<div className="megamenu-tabs">
										<ul
											className="nav nav-tabs"
											role="tablist"
										>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link active"
													id="womens-tab"
													data-bs-toggle="tab"
													data-bs-target="#womens-content-1883"
													type="button"
													aria-selected="true"
													role="tab"
												>
													WOMEN
												</button>
											</li>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link"
													id="mens-tab"
													data-bs-toggle="tab"
													data-bs-target="#mens-content-1883"
													type="button"
													aria-selected="false"
													tabIndex="-1"
													role="tab"
												>
													MEN
												</button>
											</li>
											<li
												className="nav-item"
												role="presentation"
											>
												<button
													className="nav-link"
													id="kids-tab"
													data-bs-toggle="tab"
													data-bs-target="#kids-content-1883"
													type="button"
													aria-selected="false"
													tabIndex="-1"
													role="tab"
												>
													KIDS
												</button>
											</li>
										</ul>
									</div>

									{/*Tabs Content */}
									<div className="megamenu-content tab-content">
										{/*Women Tab */}
										<div
											className="tab-pane fade show active"
											id="womens-content-1883"
											role="tabpanel"
											aria-labelledby="womens-tab"
										>
											<div className="category-layout">
												<div className="categories-section">
													<div className="category-headers">
														<h4>Clothing</h4>
														<h4>Shoes</h4>
														<h4>Accessories</h4>
														<h4>Specialty Sizes</h4>
													</div>

													<div className="category-links">
														<div className="link-row">
															<a href="#">
																Shirts &amp;
																Tops
															</a>
															<a href="#">
																Boots
															</a>
															<a href="#">
																Handbags
															</a>
															<a href="#">
																Plus Size
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Coats &amp;
																Outerwear
															</a>
															<a href="#">
																Sandals
															</a>
															<a href="#">
																Eyewear
															</a>
															<a href="#">
																Petite
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Underwear
															</a>
															<a href="#">
																Heels
															</a>
															<a href="#">Hats</a>
															<a href="#">
																Wide Shoes
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Sweatshirts
															</a>
															<a href="#">
																Loafers
															</a>
															<a href="#">
																Watches
															</a>
															<a href="#">
																Narrow Shoes
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Dresses
															</a>
															<a href="#">
																Slippers
															</a>
															<a href="#">
																Jewelry
															</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																Swimwear
															</a>
															<a href="#">
																Oxfords
															</a>
															<a href="#">
																Belts
															</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#"></a>
														</div>
													</div>
												</div>
												<div className="featured-section">
													<div className="featured-image">
														<img
															src="assets/img/product/product-f-1.webp"
															alt="Women's Heels Collection"
														/>
														<div className="featured-content">
															<h3>
																Women's
																<br />
																Bags
																<br />
																Collection
															</h3>
															<a
																href="#"
																className="btn-shop"
															>
																Shop now
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>

										{/*Men Tab */}
										<div
											className="tab-pane fade"
											id="mens-content-1883"
											role="tabpanel"
											aria-labelledby="mens-tab"
										>
											<div className="category-layout">
												<div className="categories-section">
													<div className="category-headers">
														<h4>Clothing</h4>
														<h4>Shoes</h4>
														<h4>Accessories</h4>
														<h4>Specialty Sizes</h4>
													</div>

													<div className="category-links">
														<div className="link-row">
															<a href="#">
																Shirts &amp;
																Polos
															</a>
															<a href="#">
																Sneakers
															</a>
															<a href="#">
																Watches
															</a>
															<a href="#">
																Big &amp; Tall
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Jackets &amp;
																Coats
															</a>
															<a href="#">
																Boots
															</a>
															<a href="#">
																Belts
															</a>
															<a href="#">
																Slim Fit
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Underwear
															</a>
															<a href="#">
																Loafers
															</a>
															<a href="#">Ties</a>
															<a href="#">
																Wide Shoes
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Hoodies
															</a>
															<a href="#">
																Dress Shoes
															</a>
															<a href="#">
																Wallets
															</a>
															<a href="#">
																Extended Sizes
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Suits
															</a>
															<a href="#">
																Sandals
															</a>
															<a href="#">
																Sunglasses
															</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																Activewear
															</a>
															<a href="#">
																Slippers
															</a>
															<a href="#">Hats</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#"></a>
														</div>
													</div>
												</div>
												<div className="featured-section">
													<div className="featured-image">
														<img
															src="assets/img/product/product-m-4.webp"
															alt="Men's Footwear Collection"
														/>
														<div className="featured-content">
															<h3>
																Men's
																<br />
																Footwear
																<br />
																Collection
															</h3>
															<a
																href="#"
																className="btn-shop"
															>
																Shop now
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>

										{/*Kids Tab */}
										<div
											className="tab-pane fade"
											id="kids-content-1883"
											role="tabpanel"
											aria-labelledby="kids-tab"
										>
											<div className="category-layout">
												<div className="categories-section">
													<div className="category-headers">
														<h4>Clothing</h4>
														<h4>Shoes</h4>
														<h4>Accessories</h4>
														<h4>By Age</h4>
													</div>

													<div className="category-links">
														<div className="link-row">
															<a href="#">
																T-shirts &amp;
																Tops
															</a>
															<a href="#">
																Sneakers
															</a>
															<a href="#">
																Backpacks
															</a>
															<a href="#">
																Babies (0-24
																months)
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Outerwear
															</a>
															<a href="#">
																Boots
															</a>
															<a href="#">
																Hats &amp; Caps
															</a>
															<a href="#">
																Toddlers (2-4
																years)
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Pajamas
															</a>
															<a href="#">
																Sandals
															</a>
															<a href="#">
																Socks
															</a>
															<a href="#">
																Kids (4-7 years)
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Sweatshirts
															</a>
															<a href="#">
																Slippers
															</a>
															<a href="#">
																Gloves
															</a>
															<a href="#">
																Older Kids (8-14
																years)
															</a>
														</div>
														<div className="link-row">
															<a href="#">
																Dresses
															</a>
															<a href="#">
																School Shoes
															</a>
															<a href="#">
																Scarves
															</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																Swimwear
															</a>
															<a href="#">
																Sports Shoes
															</a>
															<a href="#">
																Hair Accessories
															</a>
															<a href="#"></a>
														</div>
														<div className="link-row">
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#">
																View all
															</a>
															<a href="#"></a>
														</div>
													</div>
												</div>
												<div className="featured-section">
													<div className="featured-image">
														<img
															src="assets/img/product/product-9.webp"
															alt="Kids' New Arrivals"
														/>
														<div className="featured-content">
															<h3>
																Kids
																<br />
																New
																<br />
																Arrivals
															</h3>
															<a
																href="#"
																className="btn-shop"
															>
																Shop now
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{/*End Products Mega Menu 2 Desktop View */}
							</li>
							{/*End Products Mega Menu 2 */}

							<li>
								<a href="contact.html">Contact</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>

			{/*Announcement Bar */}
			<div className="announcement-bar py-2">
				<div className="container-fluid container-xl">
					<div className="announcement-slider swiper init-swiper">
						<div className="swiper-wrapper">
							<div className="swiper-slide">
								🚚 Free shipping on orders over $50
							</div>
							<div className="swiper-slide">
								💰 30 days money back guarantee
							</div>
							<div className="swiper-slide">
								🎁 20% off on your first order - Use code:
								FIRST20
							</div>
							<div className="swiper-slide">
								⚡ Flash Sale! Up to 70% off on selected items
							</div>
						</div>
					</div>
				</div>
			</div>

			{/*Mobile Search Form */}
			<div className="collapse" id="mobileSearch">
				<div className="container">
					<form className="search-form">
						<div className="input-group">
							<input
								type="text"
								className="form-control"
								placeholder="Search for products..."
							/>
							<button className="btn search-btn" type="submit">
								<i className="bi bi-search"></i>
							</button>
						</div>
					</form>
				</div>
			</div>
		</header>
	);
}

export default Header;
