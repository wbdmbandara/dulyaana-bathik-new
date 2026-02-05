import { redirect, useNavigate } from "react-router-dom";
import { API_URL, BACKEND_URL } from "../../config";
import { login, isAuthenticated } from "../../services/AuthService";
import React, { useState, useEffect } from "react";

let redirectPath = "/profile";
const handleSubmit = (event) => {
	event.preventDefault();

	// prepare data for send to api
	const data = {
		name: event.target.name.value,
		email: event.target.email.value,
		phone: event.target.phone.value,
		password: event.target.password.value,
		password_confirmation: event.target.confirmPassword.value,
		newsletter: event.target.newsletter.checked,
		terms: event.target.terms.checked,
	};

	var errors = [];
	if (data.name.length < 3) {
		errors.push("Name must be at least 3 characters long");
	}
	if (data.email.length < 1) {
		errors.push("Email is required");
	} else {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (!emailRegex.test(data.email)) {
			errors.push("Invalid email format");
		}
	}
	if(data.phone.length < 1){
		errors.push("Contact No is required");
	}else if (data.phone.length != 10){
		errors.push("Contact No must contain 10 numbers");
	}else {
		const phoneRegex = /^07\d{8}$/;
		if (!phoneRegex.test(data.phone)) {
			errors.push("Invalid Contact No format");
		}
	}
	if (data.password.length < 1) {
		errors.push("Password is required");
	} else {
		if (data.password.length < 8) {
			errors.push("Password must be at least 8 characters long");
		}
		if (data.password_confirmation !== data.password) {
			errors.push("Password and Confirm Password do not match");
		}
	}
	if (data.terms !== true) {
		errors.push("You must accept the terms and conditions");
	}
	if (errors.length > 0) {
		const errorList = errors.map((error) => `<li>${error}</li>`).join("");
		document.getElementById("errors").innerHTML = `<ul>${errorList}</ul>`;
		document.getElementById("errors").classList.remove("d-none");
		document.getElementById("section-header").scrollIntoView({ behavior: "smooth" });
		return;
	}else{
		document.getElementById("errors").classList.add("d-none");
	}

	// Show loading indicator
	const submitButton = event.target.querySelector("button[type='submit']");
	submitButton.disabled = true;
	submitButton.textContent = "Loading...";

	// send data to api
	fetch(`${API_URL}registerCustomer`, {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	})
		.then((response) => {
			if(response.status === 409){
				throw new Error("Email already exists");
			}
			if(response.status === 500){
				throw new Error("There was a problem with the server. Please try again later.");
			}
			if (!response.ok) {
				throw new Error("There was a problem with the fetch operation");
			}
			return response.json();
		})
		.then((data) => {
			if (data.status === 'success') {
				if(redirectPath && redirectPath !== "/profile"){
					window.location.href = "/login?registered=success&redirect=" + redirectPath;
				}else{
					window.location.href = "/login?registered=success";
				}
			} else {
				document.getElementById("errors").innerHTML = `<ul><li>${data.message}</li></ul>`;
				document.getElementById("errors").classList.remove("d-none");
				document.getElementById("section-header").scrollIntoView({ behavior: "smooth" });
			}
		})
		.catch((err) => {
			document.getElementById("errors").innerHTML = `<ul><li>${err.message}</li></ul>`;
			document.getElementById("errors").classList.remove("d-none");
			document.getElementById("section-header").scrollIntoView({ behavior: "smooth" });
		})
		.finally(() => {
			// Hide loading indicator
			submitButton.disabled = false;
			submitButton.textContent = "Create Account";
		});
};

const showPassword = (event) => {
	const passwordInput = event.target.previousSibling;
	passwordInput.type = passwordInput.type === "password" ? "text" : "password";
};

const showConfirmPassword = (event) => {
	const confirmPasswordInput = event.target.previousSibling;
	confirmPasswordInput.type = confirmPasswordInput.type === "password" ? "text" : "password";
};

function RegisterForm() {
	const navigate = useNavigate();

	// get redirect path from query params if exists
	const params = new URLSearchParams(window.location.search);
	if (params.get("redirect"))
	{
		redirectPath = params.get("redirect");
	}
	// Redirect if already authenticated
	useEffect(() => {
		if (isAuthenticated()) {
			navigate(redirectPath);
		}
	}, [navigate]);

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
								<div className="section-header mb-4 text-center" id="section-header">
									<h2>Create Your Account</h2>
									<p>
										Sign up to start shopping and enjoy
										exclusive offers
									</p>
								</div>

								<div className="errors alert alert-danger d-none" id="errors">
								</div>

								<form onSubmit={handleSubmit} action="#">
									<div className="form-group mb-3">
										<label htmlFor="name">
											Name
										</label>
										<input
											type="text"
											className="form-control"
											name="name"
											id="name"
											required=""
											placeholder="John Doe"
										/>
									</div>
									
									<div className="form-group mb-3">
										<label htmlFor="phone">
											Contact No
										</label>
										<input
											type="text"
											className="form-control"
											name="phone"
											id="phone"
											required=""
											placeholder="07xxxxxxxx"
										/>
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
											<i className="bi bi-eye toggle-password" onClick={showPassword}></i>
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
											<i className="bi bi-eye toggle-password" onClick={showConfirmPassword}></i>
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
											<a href={redirectPath ? "login?redirect=" + redirectPath : "/login"}>Sign in</a>
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
