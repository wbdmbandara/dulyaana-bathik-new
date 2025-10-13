import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

function Login() {
	const handleSubmit = (event) => {
		event.preventDefault();
		const email = event.target.email.value;
		const password = event.target.password.value;

		var errors = [];
		if (email.length < 1) {
			errors.push("Email is required");
		}
		if (password.length < 1) {
			errors.push("Password is required");
		}
		if (errors.length > 0) {
			const errorList = errors.map((error) => `<li>${error}</li>`).join("");
			document.getElementById("errors").innerHTML = `<ul>${errorList}</ul>`;
			document.getElementById("errors").classList.remove("d-none");
			document.getElementById("section-header").scrollIntoView({ behavior: "smooth" });
			return;
		} else {
			document.getElementById("errors").classList.add("d-none");
		}

		fetch(`${API_URL}login`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ email, password }),
		})
			.then((response) => {
				if(response.status === 401) {
					throw new Error("Invalid email or password.");
				}
				return response.json();
			})
			.then((data) => {
				if (data.status === "success") {
					// Handle successful login
					window.location.href = "/dashboard";
				} else {
					throw new Error(data.message || "Login failed. Try again.");
				}
			})
			.catch((err) => {
				document.getElementById("errors").innerHTML = `<p>${err.message}</p>`;
				document.getElementById("errors").classList.remove("d-none");
				document.getElementById("section-header").scrollIntoView({ behavior: "smooth" });
			});
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
								<div className="login-header text-center" id="section-header">
									<h2>Login</h2>
									{new URLSearchParams(window.location.search).get("registered") === "success"
										? <p className="alert alert-success">Registration successful! Please log in to continue shopping.</p>
										: <p>Welcome back! Please enter your details</p>}
								</div>

								<div className="errors alert alert-danger d-none" id="errors">
								</div>

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
										/>
									</div>

									<div className="mb-3">
										<div className="d-flex justify-content-between">
											<label
												htmlFor="password"
												className="form-label"
											>
												Password
											</label>
											<a href="/forgot-password" className="forgot-link">
												Forgot password?
											</a>
										</div>
										<input
											type="password"
											className="form-control"
											id="password"
											placeholder="Enter your password"
											required=""
											autoComplete="current-password"
										/>
									</div>

									<div className="d-grid gap-2 mb-4">
										<button
											type="submit"
											className="btn btn-primary"
										>
											Sign in
										</button>
										<button
											type="button"
											className="btn btn-outline d-none"
										>
											<i className="bi bi-google me-2"></i>
											Sign in with Google
										</button>
									</div>

									<div className="signup-link text-center">
										<span>Don't have an account?</span>
										<a href="/register">Sign up for free</a>
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

export default Login;
