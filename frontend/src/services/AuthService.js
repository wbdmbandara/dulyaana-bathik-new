import axios from "axios";
import { API_URL } from "../config";

export const login = async (data) => {
	try {
		// console.log("Making login request to:", `${API_URL}login`);
		// console.log("Login data:", data);

		const response = await axios.post(`${API_URL}login`, data, {
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
			},
		});

		// console.log("Login response:", response);

		// Handle successful login
		if (response.data.status === "success") {
			// Store user data in localStorage
			localStorage.setItem("user", JSON.stringify(response.data.user));
			localStorage.setItem("isLoggedIn", "true");

			return {
				status: 200,
				data: response.data,
			};
		}

		return { status: response.status, data: response.data };
	} catch (error) {
		// console.error("Login error details:", {
		// 	message: error.message,
		// 	response: error.response?.data,
		// 	status: error.response?.status,
		// });

		if (error.response?.status === 401) {
			return {
				status: 401,
				errors: [
					error.response.data.message || "Invalid email or password",
				],
			};
		} else if (error.response?.status === 422) {
			return {
				status: 422,
				errors: Object.values(error.response.data.errors || {}).flat(),
			};
		} else if (error.response?.status === 500) {
			return {
				status: 500,
				errors: ["Server error occurred. Please try again later."],
			};
		}

		throw error;
	}
};

export const register = async (data) => {
	try {
		const response = await axios.post(`${API_URL}registerCustomer`, data, {
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
			},
		});

		return { status: 201, data: response.data };
	} catch (error) {
		if (error.response?.status === 422) {
			return {
				status: 422,
				errors: Object.values(error.response.data.errors || {}).flat(),
			};
		} else if (error.response?.status === 409) {
			return { status: 409, errors: [error.response.data.message] };
		}
		throw error;
	}
};

export const logout = () => {
	localStorage.removeItem("user");
	localStorage.removeItem("isLoggedIn");
	return Promise.resolve({ status: 200, message: "Logged out successfully" });
};

export const getCurrentUser = () => {
	const user = localStorage.getItem("user");
	return user ? JSON.parse(user) : null;
};

export const isAuthenticated = () => {
	return localStorage.getItem("isLoggedIn") === "true";
};
