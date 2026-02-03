import React from "react";
import {
	API_URL,
	BACKEND_URL,
	formatNumber,
	formatCurrency,
} from "../../config";

function AboutCompany() {
	return (
		<section id="about-2" className="about-2 section">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
				<div className="row mb-lg-5">
					<span className="text-uppercase small-title mb-2">
						About Our Heritage
					</span>
					<div className="col-lg-6">
						<h2 className="about-title">
							Preserving the Soul of Sri Lankan Batik Artistry
						</h2>
					</div>
					<div className="col-lg-6 description-wrapper">
						<p className="about-description">
							Based in Giriulla, Dulyaana Bathik is more than a
							clothing brand; we are the guardians of a
							traditional craft. Every saree in our collection is
							100% handmade and hand-painted, ensuring that you
							wear a piece of authentic Sri Lankan culture that
							blends timeless heritage with modern elegance.
						</p>
					</div>
				</div>

				<div className="row g-4">
					{/* Authentic Craftsmanship */}
					<div
						className="col-lg-4 aos-init aos-animate"
						data-aos="fade-up"
						data-aos-delay="200"
					>
						<div className="content-card">
							<div className="card-image">
								<img
									src={`${BACKEND_URL}assets/about/about01.jpg`}
									alt="Hand-painted Batik Process"
									className="img-fluid"
								/>
							</div>
							<div className="card-content">
								<h3>100% Hand-Painted</h3>
								<p>
									Experience the beauty of human touch. Each
									design is meticulously crafted by local
									artisans, making every saree a unique
									masterpiece with its own story.
								</p>
								<a href="#" className="read-more d-none">
									Explore Craft{" "}
									<i className="bi bi-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
					{/* End Content Card */}

					{/* Modern Elegance */}
					<div
						className="col-lg-4 aos-init aos-animate"
						data-aos="fade-up"
						data-aos-delay="300"
					>
						<div className="content-card">
							<div className="card-image">
								<img
									src={`${BACKEND_URL}assets/about/about02.jpg`}
									alt="Modern Batik Fashion"
									className="img-fluid"
								/>
							</div>
							<div className="card-content">
								<h3>Contemporary Design</h3>
								<p>
									We blend traditional batik techniques with
									current fashion trends, creating vibrant,
									high-quality sarees perfect for the modern
									Sri Lankan woman.
								</p>
								<a href="#" className="read-more d-none">
									View Collections{" "}
									<i className="bi bi-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
					{/* End Content Card */}

					{/* Trust & Authenticity */}
					<div
						className="col-lg-4 aos-init aos-animate"
						data-aos="fade-up"
						data-aos-delay="400"
					>
						<div className="content-card">
							<div className="card-image">
								<img
									src={`${BACKEND_URL}assets/about/about03.jpg`}
									alt="Dulyaana Bathik Originality"
									className="img-fluid"
								/>
							</div>
							<div className="card-content">
								<h3>Originality Guaranteed</h3>
								<p>
									With a community of over 114K followers, we
									stand for authenticity. Beware of
									imitations; our designs are exclusive to
									Dulyaana Bathik.
								</p>
								<a href="#" className="read-more d-none">
									Our Story{" "}
									<i className="bi bi-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
					{/* End Content Card */}
				</div>
			</div>
		</section>
	);
}

export default AboutCompany;
