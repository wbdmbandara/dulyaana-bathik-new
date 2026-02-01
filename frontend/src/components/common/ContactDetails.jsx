import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";
import { useSnackbar } from "../../context/SnackbarContext";

function ContactDetails() {
	const [ContactDetails, setContactDetails] = useState([]);
	const [contactForm, setContactForm] = useState({
		name: "",
		email: "",
		subject: "",
		message: ""
	});
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const [mapLoaded, setMapLoaded] = useState(false);
    const { showSnackbar } = useSnackbar();

	useEffect(() => {
		fetchContactDetails();
	}, []);

	const fetchContactDetails = async () => {
		try {
			setLoading(true);
			const response = await fetch(`${API_URL}getContactDetails`);

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			const data = await response.json();
			if (data.success) {
				setContactDetails(data.contactDetails || []);
			} else {
				throw new Error(
					data.message || "Failed to fetch contact details"
				);
			}
		} catch (err) {
			console.error("Error fetching contact details:", err);
			setError(err.message);
		} finally {
			setLoading(false);
		}
	};

    const submitForm = async (e) => {
        e.preventDefault();

        if (loading) {
            return;
        }

        try {
            setLoading(true);
            const response = await fetch(`${API_URL}submitContactForm`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(contactForm),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            if (data.success) {
                showSnackbar("Your message has been sent. Thank you!", "success");
                setContactForm({
                    name: "",
                    email: "",
                    subject: "",
                    message: ""
                });
            } else {
                throw new Error(data.message || "Failed to submit form");
            }
        } catch (err) {
            console.error("Error submitting contact form:", err);
            showSnackbar(err.message || "Failed to submit form", "error");
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

	if (loading) {
		return (
			<div>
				<section id="contact-2" className="contact-2 section">
					<div
						className="container"
						data-aos="fade-up"
						data-aos-delay="100"
					>
						<div className="row g-4">
							<div className="col-12 text-center">
								<div
									className="spinner-border text-primary"
									role="status"
								>
									<span className="visually-hidden">
										Loading contact Details...
									</span>
								</div>
							</div>
						</div>
					</div>
				</section>{" "}
			</div>
		);
	}

	return (
		<div>
			{/* Contact Section */}
			<section id="contact-2" className="contact-2 section">
				<div
					className="mb-4 aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="200"
					style={{
						position: "relative",
						minHeight: "450px",
						backgroundColor: "#e9ecef",
					}}
				>
					{!mapLoaded && (
						<div
							style={{
								position: "absolute",
								top: 0,
								left: 0,
								right: 0,
								bottom: 0,
								display: "flex",
								alignItems: "center",
								justifyContent: "center",
								backgroundColor: "#e9ecef",
								zIndex: 1,
								borderRadius: "4px",
							}}
						>
							<div className="text-center">
								<div
									className="spinner-border text-primary mb-3"
									role="status"
								>
									<span className="visually-hidden">
										Loading map...
									</span>
								</div>
								<p className="text-muted">
									Loading Google Maps...
								</p>
							</div>
						</div>
					)}
					<iframe
						style={{
							border: 0,
							width: "100%",
							height: "450px",
							borderRadius: "4px",
						}}
						src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31656.615497570598!2d80.07800155545043!3d7.345257453235199!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2df000e6cbdb3%3A0x827859e7c97e4abb!2sDulyaana%20Bathik!5e0!3m2!1sen!2slk!4v1769871128001!5m2!1sen!2slk"
						allowFullScreen=""
						loading="lazy"
						referrerPolicy="no-referrer-when-downgrade"
						onLoad={() => setMapLoaded(true)}
						title="Dulyaana Bathik Location"
					></iframe>
				</div>
				{/* End Google Maps */}

				<div
					className="container aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row gy-4">
						<div className="col-lg-4">
							<div
								className="info-item d-flex aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="300"
							>
								<i className="bi bi-geo-alt flex-shrink-0"></i>
								<div>
									<h3>Address</h3>
									<p>{ContactDetails.address}</p>
								</div>
							</div>
							{/* End Info Item */}

							<div
								className="info-item d-flex aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="400"
							>
								<i className="bi bi-telephone flex-shrink-0"></i>
								<div>
									<h3>Call Us</h3>
									{ContactDetails.phone01 && (
										<p>
											<a
												href={`tel:${ContactDetails.phone01}`}
											>
												{ContactDetails.phone01}
											</a>
										</p>
									)}
									{ContactDetails.phone02 && (
										<p>
											<a
												href={`tel:${ContactDetails.phone02}`}
											>
												{ContactDetails.phone02}
											</a>
										</p>
									)}
								</div>
							</div>
							{/* End Info Item */}

							<div
								className="info-item d-flex aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="500"
							>
								<i className="bi bi-envelope flex-shrink-0"></i>
								<div>
									<h3>Email Us</h3>
									{ContactDetails.email01 && (
										<p>
											<a
												href={`mailto:${ContactDetails.email01}`}
											>
												{ContactDetails.email01}
											</a>
										</p>
									)}
									{ContactDetails.email02 && (
										<p>
											<a
												href={`mailto:${ContactDetails.email02}`}
											>
												{ContactDetails.email02}
											</a>
										</p>
									)}
								</div>
							</div>
							{/* End Info Item */}
						</div>

						<div className="col-lg-8">
							<form
								className="php-email-form aos-init aos-animate"
								data-aos="fade-up"
								data-aos-delay="200"
								onSubmit={submitForm}
							>
								<div className="row gy-4">
									<div className="col-md-6">
										<input
											type="text"
											name="name"
											className="form-control"
											placeholder="Your Name"
											required
											value={contactForm.name}
											onChange={(e) =>
												setContactForm({
													...contactForm,
													name: e.target.value,
												})
											}
										/>
									</div>

									<div className="col-md-6">
										<input
											type="email"
											className="form-control"
											name="email"
											placeholder="Your Email"
											required
											value={contactForm.email}
											onChange={(e) =>
												setContactForm({
													...contactForm,
													email: e.target.value,
												})
											}
										/>
									</div>

									<div className="col-md-12">
										<input
											type="text"
											className="form-control"
											name="subject"
											placeholder="Subject"
											required
											value={contactForm.subject}
											onChange={(e) =>
												setContactForm({
													...contactForm,
													subject: e.target.value,
												})
											}
										/>
									</div>

									<div className="col-md-12">
										<textarea
											className="form-control"
											name="message"
											rows="6"
											placeholder="Message"
											required
											value={contactForm.message}
											onChange={(e) =>
												setContactForm({
													...contactForm,
													message: e.target.value,
												})
											}
										></textarea>
									</div>

									<div className="col-md-12 text-center">
										<div className="loading">Loading</div>
										<div
											className="error-message d-none"
											id="errorMessage"
										>
											Failed to submit form
										</div>
										<div
											className="sent-message d-none"
											id="successMessage"
										>
											Your message has been sent. Thank
											you!
										</div>

										<button type="submit">
											Send Message
										</button>
									</div>
								</div>
							</form>
						</div>
						{/* End Contact Form */}
					</div>
				</div>
			</section>
			{/* /Contact Section */}
		</div>
	);
}

export default ContactDetails;
