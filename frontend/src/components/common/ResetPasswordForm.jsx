import { useNavigate } from "react-router-dom";
import { login, isAuthenticated } from "../../services/AuthService";
import { API_URL, BACKEND_URL } from "../../config";
import React, { useState, useEffect } from "react";

const showPassword = (event) => {
	const passwordInput = event.target.previousSibling;
	passwordInput.type =
		passwordInput.type === "password" ? "text" : "password";
};

const showConfirmPassword = (event) => {
	const confirmPasswordInput = event.target.previousSibling;
	confirmPasswordInput.type =
		confirmPasswordInput.type === "password" ? "text" : "password";
};

function ResetPassword() {
	const [email, setEmail] = useState("");
	const [errors, setErrors] = useState([]);
	const [successMsg, setSuccessMsg] = useState([]);
	const [localErrors, setLocalErrors] = useState([]);
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

	var token = "";
	if (params.get("token")) {
		token = params.get("token");
	} else {
		navigate("/forgot-password");
	}

	// get customer email using token
	useEffect(() => {
		const fetchEmail = async () => {
			try {
				const response = await fetch(
					`${API_URL}reset-password?token=${token}`
				);
				if (!response.ok) {
					throw new Error("Failed to fetch email");
				}
				const data = await response.json();
				setEmail(data.email);
			} catch (error) {
				console.error("Error fetching email:", error);
				setErrors(["Failed to fetch email. Please try again."]);
			}
		};

		fetchEmail();
	}, [token]);

	const handleSubmit = async (event) => {
		event.preventDefault();
		setIsLoading(true);
		setLocalErrors([]); // Clear previous errors
		setSuccessMsg([]);

		const payload = {
			token: token,
			email: event.target.email.value,
			password: event.target.password.value,
			password_confirmation: event.target.confirmPassword.value,
		};

		// Validation
		if (payload.password.length < 8) {
			setLocalErrors(["Password must be at least 8 characters long"]);
			setIsLoading(false);
			return;
		}
		if (payload.password_confirmation.length < 8) {
			setLocalErrors([
				"Confirm Password must be at least 8 characters long",
			]);
			setIsLoading(false);
			return;
		}
		if (payload.password != payload.password_confirmation) {
			setLocalErrors(["Password and Confirm Password do not match"]);
			setIsLoading(false);
			return;
		}

		try {
			const response = await fetch(`${API_URL}reset-password`, {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify(payload), // No extra nesting
			});

			const result = await response.json();

			if (response.ok) {
				setSuccessMsg([
					"Your Password reset successfully. Login to your account with new password.",
				]);
				// navigate to login page after 5 seconds
				setTimeout(() => navigate("/login?pw-reset=success"), 5000);
			} else {
				setLocalErrors([result.message || "Reset failed"]);
			}
		} catch (error) {
			setLocalErrors(["An unexpected error occurred."]);
		} finally {
			setIsLoading(false);
		}
	};

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
								<div
									className="section-header mb-4 text-center"
									id="section-header"
								>
									<h2>Set New Password</h2>
									<p>
										Choose a new password for your Dulyaana
										Bathik account to finish the recovery
										process and get back to shopping.
									</p>
								</div>

								<div
									className="errors alert alert-danger d-none"
									id="errors"
								></div>

								{localErrors.length > 0 && (
									<div
										className="errors alert alert-danger"
										id="localerrors"
									>
										<ul>
											{localErrors.map((error, index) => (
												<li key={index}>{error}</li>
											))}
										</ul>
									</div>
								)}

								{successMsg.length > 0 && (
									<div
										className="success alert alert-success"
										id="success"
									>
										<ul>
											{successMsg.map((error, index) => (
												<li key={index}>{error}</li>
											))}
										</ul>
									</div>
								)}

								<form onSubmit={handleSubmit} action="#">
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
											readOnly
											placeholder="you@example.com"
											value={email}
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
											<i
												className="bi bi-eye toggle-password"
												onClick={showPassword}
											></i>
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
											<i
												className="bi bi-eye toggle-password"
												onClick={showConfirmPassword}
											></i>
										</div>
									</div>

									<div className="text-center mb-4">
										<button
											type="submit"
											className="btn btn-primary w-100"
										>
											Update Password
										</button>
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

export default ResetPassword;
