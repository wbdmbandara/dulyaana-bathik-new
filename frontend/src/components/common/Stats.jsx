import React, { useEffect } from "react";

function Stats() {
	useEffect(() => {
		const initializePureCounter = async () => {
			const { default: PureCounter } = await import(
				"@srexi/purecounterjs"
			);
			new PureCounter();
		};
		initializePureCounter();
	}, []);

	return (
		<section id="stats" className="stats section light-background">
			<div
				className="container aos-init aos-animate"
				data-aos="fade-up"
				data-aos-delay="100"
			>
				<div className="row gy-4">
					{/* Customer Satisfaction */}
					<div className="col-lg-3 col-md-6">
						<div className="stats-item">
							<i className="bi bi-star-fill"></i>
							<span
								data-purecounter-start="0"
								data-purecounter-end="96"
								data-purecounter-duration="1"
								className="purecounter"
							>
								96
							</span>
							<span>%</span>
							<p>
								<strong>Recommendation Rate</strong>{" "}
								<span>Based on customer reviews</span>
							</p>
						</div>
					</div>
					{/* End Stats Item */}

					{/* Craftsmanship */}
					<div className="col-lg-3 col-md-6">
						<div className="stats-item">
							<i className="bi bi-palette"></i>
							<span
								data-purecounter-start="0"
								data-purecounter-end="100"
								data-purecounter-duration="1"
								className="purecounter"
							>
								100
							</span>
							<span>%</span>
							<p>
								<strong>Handmade Artistry</strong>{" "}
								<span>Authentic Batik designs</span>
							</p>
						</div>
					</div>
					{/* End Stats Item */}

					{/* Experience/Scale */}
					<div className="col-lg-3 col-md-6">
						<div className="stats-item">
							<i className="bi bi-truck"></i>
							<span
								data-purecounter-start="0"
								data-purecounter-end="25"
								data-purecounter-duration="1"
								className="purecounter"
							>
								25
							</span>
							<span>+</span>
							<p>
								<strong>Districts Reached</strong>{" "}
								<span>Islandwide delivery</span>
							</p>
						</div>
					</div>
					{/* End Stats Item */}

					{/* Social Media Following */}
					<div className="col-lg-3 col-md-6">
						<div className="stats-item">
							<i className="bi bi-facebook"></i>
							<span
								data-purecounter-start="0"
								data-purecounter-end="114"
								data-purecounter-duration="1"
								className="purecounter"
							>
								114
							</span>
							<span>K+</span>
							<p>
								<strong>Facebook Followers</strong>{" "}
								<span>Growing community</span>
							</p>
						</div>
					</div>
					{/* End Stats Item */}
				</div>
			</div>
		</section>
	);
}

export default Stats;
