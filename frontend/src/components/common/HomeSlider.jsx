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
			<section
				className="ecommerce-hero-2 hero section"
				id="hero"
				style={{
					scrollSnapAlign: "start",
					display: "flex",
					alignItems: "center",
					justifyContent: "center",
				}}
			>
				<div className="container-fluid p-0">
					<div
						className="hero-slider swiper init-swiper"
						data-aos="fade-up"
						style={{ height: "100%" }}
					>
						<div className="swiper-wrapper" style={{ height: "100%" }}>
							{loading ? (
								<div className="swiper-slide" style={{ height: "100%" }}>
									<div
										className="slide-image"
										style={{
											display: "flex",
											alignItems: "center",
											justifyContent: "center",
											height: "100%",
										}}
									>
										<p>Loading...</p>
									</div>
								</div>
							) : (
								sliders.map((slider, index) => (
									<div
										key={slider.id || index}
										className="swiper-slide"
										style={{ height: "100%" }}
									>
										<div className="slide-image" style={{ height: "100%" }}>
											<img
												src={`${BACKEND_URL}${slider.image_path}`}
												alt={
													slider.title || `Hero Slide ${index + 1}`
												}
												className="img-fluid w-100"
												style={{ height: "100%", objectFit: "cover" }}
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
