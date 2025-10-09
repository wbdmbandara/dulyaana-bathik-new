import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function Footer() {
	const [footerContent, setFooterContent] = useState({});
	const [socialLinks, setSocialLinks] = useState([]);
	const [menu01Links, setMenu01Links] = useState([]);
	const [menu02Links, setMenu02Links] = useState([]);
	const [footerLinks, setFooterLinks] = useState([]);
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchData = async () => {
			try {
				const response = await fetch(`${API_URL}getFooter`);
				const data = await response.json();
				setFooterContent(data.footerContent);
				setSocialLinks(data.socialLinks);
				setMenu01Links(data.menu01Links);
				setMenu02Links(data.menu02Links);
				setFooterLinks(data.footerLinks);
			} catch (error) {
				console.error("Error fetching footer data:", error);
			} finally {
				setLoading(false);
			}
		};

		fetchData();
	}, []);

	const openingHours = footerContent.open_time;
	const hoursArray = openingHours ? openingHours.split("\n") : [];

	return (
		<div>
			<footer id="footer" className="footer light-background">
				<div className="footer-main">
					<div className="container">
						<div className="row gy-4">
							<div className="col-lg-4 col-md-6">
								<div className="footer-widget footer-about">
									<a href="/" className="logo">
										<span className="sitename">
											{footerContent.site_name}
										</span>
									</a>
									<p>
										{footerContent.about_text}
									</p>

									<div className="social-links mt-4">
										<h5>Connect With Us</h5>
										<div className="social-icons">
											{socialLinks.map((link, index) => (
												<a key={index} href={link.url} aria-label={link.name}>
													<i className={`${link.icon}`}></i>
												</a>
											))}
										</div>
									</div>
								</div>
							</div>

							<div className="col-lg-2 col-md-6 col-sm-6">
								<div className="footer-widget">
									<h4>Shop</h4>
									<ul className="footer-links">
										{menu01Links.map((link, index) => (
											<li key={index}>
												<a href={link.url}>{link.title}</a>
											</li>
										))}
									</ul>
								</div>
							</div>

							<div className="col-lg-2 col-md-6 col-sm-6">
								<div className="footer-widget">
									<h4>Support</h4>
									<ul className="footer-links">
										{menu02Links.map((link, index) => (
											<li key={index}>
												<a href={link.url}>{link.title}</a>
											</li>
										))}
									</ul>
								</div>
							</div>

							<div className="col-lg-4 col-md-6">
								<div className="footer-widget">
									<h4>Contact Information</h4>
									<div className="footer-contact">
										<div className="contact-item">
											<i className="bi bi-geo-alt"></i>
											<span>
												{footerContent.address}
											</span>
										</div>
										<div className="contact-item">
											<i className="bi bi-telephone"></i>
											<span>{footerContent.phone01}</span>
											<br />
											<span>{footerContent.phone02}</span>
										</div>
										<div className="contact-item">
											<i className="bi bi-envelope"></i>
											<span>
												<a href={`mailto:${footerContent.email01}`}
												>
													{footerContent.email01}
												</a>
												{footerContent.email02 && (
													<>
														<br />
														<a href={`mailto:${footerContent.email02}`}>
															{footerContent.email02}
														</a>
													</>
												)}
											</span>
										</div>
										<div className="contact-item">
											<i className="bi bi-clock"></i>
											<span>
												{hoursArray.map((line, index) => (
													<span key={index}>
														{line}
														<br />
													</span>
												))}
											</span>
										</div>
									</div>

									<div className="app-buttons mt-4">
										<a href={footerContent.app_store_link} className="app-btn">
											<i className="bi bi-apple"></i>
											<span>App Store</span>
										</a>
										<a href={footerContent.google_play_link} className="app-btn">
											<i className="bi bi-google-play"></i>
											<span>Google Play</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div className="footer-bottom">
					<div className="container">
						<div className="row gy-3 align-items-center">
							<div className="col-lg-6 col-md-12">
								<div className="copyright">
									<p>
										© {new Date().getFullYear()} <span>Copyright</span>{" "}
										<strong className="sitename">
											{footerContent.site_name}
										</strong>
										. All Rights Reserved.
									</p>
								</div>
								<div className="credits mt-1">
									Developed by{" "}
									<a href="https://hyperflex.lk/" target="_blank">
										HyperFlex Innovation
									</a>
								</div>
							</div>

							<div className="col-lg-6 col-md-12">
								<div className="d-flex flex-wrap justify-content-lg-end justify-content-center align-items-center gap-4">
									<div className="payment-methods d-none">
										<div className="payment-icons">
											<i
												className="bi bi-credit-card"
												aria-label="Credit Card"
											></i>
											<i
												className="bi bi-paypal"
												aria-label="PayPal"
											></i>
											<i
												className="bi bi-apple"
												aria-label="Apple Pay"
											></i>
											<i
												className="bi bi-google"
												aria-label="Google Pay"
											></i>
											<i
												className="bi bi-shop"
												aria-label="Shop Pay"
											></i>
											<i
												className="bi bi-cash"
												aria-label="Cash on Delivery"
											></i>
										</div>
									</div>

									<div className="legal-links">
										{footerLinks.map((link, index) => (
											<a key={index} href={link.url} className="me-3">
												{link.title}
											</a>
										))}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	);
}

export default Footer;
