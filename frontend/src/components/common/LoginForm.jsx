import { useNavigate } from "react-router-dom";
import { API_URL, BACKEND_URL } from "../../config";
import { login } from "../../services/AuthService";
import React, { useState, useEffect } from "react";

function Login() {
	const [email, setEmail] = useState("");
	const [password, setPassword] = useState("");
	const [errors, setErrors] = useState([]);
	const navigate = useNavigate();

	const handleSubmit = (event) => {
		event.preventDefault();

        // console.log({ email, password })
        login({
            email,
            password
        })
            .then((response) => {
                if (response.status === 200) {
                    // console.log("Login successful:", response.data);
                    setErrors([]);
                    localStorage.setItem('user', JSON.stringify(response.data.user));
                    localStorage.setItem('isLoggedIn', 'true');
                    navigate('/profile');
                } else {
                    console.error("Unexpected response status:", response.status);
                    setErrors(response.errors || []);
                }
            })
            .catch((error) => {
                // console.error("Login failed:", error);
                setErrors(error.errors || []);
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

								{errors.length > 0 && (
									<div className="errors alert alert-danger" id="errors">
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
											onChange={(e) => setEmail(e.target.value)}
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
											onChange={(e) => setPassword(e.target.value)}
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
