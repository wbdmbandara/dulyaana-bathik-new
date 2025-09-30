// import React from "react";
import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function HomeSlider() {
	const [sliders, setSliders] = useState([]);
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchSliders = async () => {
			try {
				const response = await fetch(`${API_URL}getSlider`);
				const data = await response.json();
				setSliders(data);
			} catch (error) {
				console.error("Error fetching sliders:", error);
			} finally {
				setLoading(false);
			}
		};

		fetchSliders();
	}, []);

	return (
		<div>
			{/* Hero Section */}
			<section className="ecommerce-hero-2 hero section" id="hero">
				<div className="container">
					<div
						className="hero-slider swiper init-swiper"
						data-aos="fade-up"
					>
						<div className="swiper-wrapper">
							{loading ? (
								<div className="swiper-slide">
									<div className="slide-image">
										<p>Loading...</p>
									</div>
								</div>
							) : (
								sliders.map((slider, index) => (
									<div
										key={slider.id || index}
										className="swiper-slide"
									>
										<div className="slide-image">
											<img
												src={`${BACKEND_URL}${slider.image_path}`}
												alt={
													slider.title ||
													`Hero Slide ${index + 1}`
												}
												className="img-fluid"
											/>
										</div>
									</div>
								))
							)}
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
