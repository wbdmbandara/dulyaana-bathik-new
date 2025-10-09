import React from "react";

function RegisterForm() {
	return (
		<div>
			<section id="register" className="register section">
				<div
					className="container aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row justify-content-center">
						<div className="col-lg-6">
							<div
								className="registration-form-wrapper aos-init aos-animate"
								data-aos="zoom-in"
								data-aos-delay="200"
							>
								<div className="section-header mb-4 text-center">
									<h2>Create Your Account</h2>
									<p>
										Sign up to start shopping and enjoy
										exclusive offers
									</p>
								</div>

								<form action="#" method="POST">
									<div className="row">
										<div className="col-md-6 mb-3">
											<div className="form-group">
												<label htmlFor="firstName">
													First Name
												</label>
												<input
													type="text"
													className="form-control"
													name="firstName"
													id="firstName"
													required=""
													minLength="2"
													placeholder="John"
												/>
											</div>
										</div>

										<div className="col-md-6 mb-3">
											<div className="form-group">
												<label htmlFor="lastName">
													Last Name
												</label>
												<input
													type="text"
													className="form-control"
													name="lastName"
													id="lastName"
													required=""
													minLength="2"
													placeholder="Doe"
												/>
											</div>
										</div>
									</div>

									<div className="form-group mb-3">
										<label htmlFor="email">
											Email Address
										</label>
										<input
											type="email"
											className="form-control"
											name="email"
											id="email"
											required=""
											placeholder="you@example.com"
										/>
									</div>

									<div className="form-group mb-3">
										<label htmlFor="password">
											Password
										</label>
										<div className="password-input">
											<input
												type="password"
												className="form-control"
												name="password"
												id="password"
												required=""
												minLength="8"
												placeholder="At least 8 characters"
											/>
											<i className="bi bi-eye toggle-password"></i>
										</div>
										<small className="password-requirements">
											Must be at least 8 characters long
											and include uppercase, lowercase,
											number, and special character
										</small>
									</div>

									<div className="form-group mb-4">
										<label htmlFor="confirmPassword">
											Confirm Password
										</label>
										<div className="password-input">
											<input
												type="password"
												className="form-control"
												name="confirmPassword"
												id="confirmPassword"
												required=""
												minLength="8"
												placeholder="Repeat your password"
											/>
											<i className="bi bi-eye toggle-password"></i>
										</div>
									</div>

									<div className="form-group mb-4">
										<div className="form-check">
											<input
												className="form-check-input"
												type="checkbox"
												name="newsletter"
												id="newsletter"
											/>
											<label
												className="form-check-label"
												htmlFor="newsletter"
											>
												Subscribe to our newsletter for
												exclusive offers and updates
											</label>
										</div>
									</div>

									<div className="form-group mb-4">
										<div className="form-check">
											<input
												className="form-check-input"
												type="checkbox"
												name="terms"
												id="terms"
												required=""
											/>
											<label
												className="form-check-label"
												htmlFor="terms"
											>
												I agree to the{" "}
												<a href="#">Terms of Service</a>{" "}
												and{" "}
												<a href="#">Privacy Policy</a>
											</label>
										</div>
									</div>
									<div className="text-center mb-4">
										<button
											type="submit"
											className="btn btn-primary w-100"
										>
											Create Account
										</button>
									</div>

									<div className="text-center">
										<p className="mb-0">
											Already have an account?{" "}
											<a href="#">Sign in</a>
										</p>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	);
}

export default RegisterForm;
