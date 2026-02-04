import { useNavigate } from "react-router-dom";
import { login, isAuthenticated } from "../../services/AuthService";
import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function ForgotPassword() {
	const [email, setEmail] = useState("");
	const [errors, setErrors] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const navigate = useNavigate();
	let redirectPath = "/profile";

	// get redirect path from query params if exists
	const params = new URLSearchParams(window.location.search);
	if (params.get("redirect")) {
		redirectPath = params.get("redirect");
	}

	// Redirect if already authenticated
	useEffect(() => {
		if (isAuthenticated()) {
			navigate(redirectPath);
		}
	}, [navigate]);

	const handleSubmit = async (event) => {
		event.preventDefault();
		setIsLoading(true);
		setErrors([]);

		try {
			// post email to forgot-password
            const response = await fetch(
                `${API_URL}forgot-password`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ email }),
                }
            );
		} catch (error) {
			console.error("Forgot password failed:", error);
			setErrors(["An unexpected error occurred. Please try again."]);
		} finally {
			setIsLoading(false);
		}
	};

	return (
		<div>
			<section id="login" className="login section">
				<div
					className="container aos-init aos-animate"
					data-aos="fade-up"
					data-aos-delay="100"
				>
					<div className="row justify-content-center">
						<div
							className="col-lg-5 col-md-8 aos-init aos-animate"
							data-aos="zoom-in"
							data-aos-delay="200"
						>
							<div className="login-form-wrapper">
								<div
									className="login-header text-center"
									id="section-header"
								>
									<h2>Forgot Password</h2>
									<p>
										Please enter your email address to
										receive a password reset link.
									</p>
								</div>

								{errors.length > 0 && (
									<div
										className="errors alert alert-danger"
										id="errors"
									>
										<ul>
											{errors.map((error, index) => (
												<li key={index}>{error}</li>
											))}
										</ul>
									</div>
								)}

								<form onSubmit={handleSubmit}>
									<div className="mb-4">
										<label
											htmlFor="email"
											className="form-label"
										>
											Email
										</label>
										<input
											type="email"
											className="form-control"
											id="email"
											placeholder="Enter your email"
											required=""
											autoComplete="email"
											onChange={(e) =>
												setEmail(e.target.value)
											}
										/>
									</div>

									<div className="d-grid gap-2 mb-4">
										<button
											type="submit"
											className="btn btn-primary"
											disabled={isLoading}
										>
											{isLoading
												? "Sending..."
												: "Send Reset Link"}
										</button>
									</div>
									<div className="signup-link text-center">
										<span>Back to</span>
										<a
											href={
												redirectPath
													? "login?redirect=" +
													  redirectPath
													: "/login"
											}
										>
											Login
										</a>
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

export default ForgotPassword;
