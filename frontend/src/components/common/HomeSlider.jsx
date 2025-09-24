import React from "react";
import { API_URL } from "../../config";

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
							{/* Image Slide 1 */}
							<div className="swiper-slide">
								<div className="slide-image">
									<img
										src={`${API_URL}assets/slider/slider 1.png`}
										alt="Hero Slide 1"
										className="img-fluid"
									/>
								</div>
							</div>

							{/* Image Slide 2 */}
							<div className="swiper-slide">
								<div className="slide-image">
									<img
										src={`${API_URL}assets/slider/slider 2.png`}
										alt="Hero Slide 2"
										className="img-fluid"
									/>
								</div>
							</div>

							{/* Image Slide 3 */}
							<div className="swiper-slide">
								<div className="slide-image">
									<img
										src={`${API_URL}assets/slider/slider 3.png`}
										alt="Hero Slide 3"
										className="img-fluid"
									/>
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
