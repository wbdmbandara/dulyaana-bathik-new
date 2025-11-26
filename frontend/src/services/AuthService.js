import axios from "axios";
import { API_URL } from "../config";

// Token management constants
const TOKEN_STORAGE_KEY = "auth_token";
const USER_STORAGE_KEY = "user";
const LOGIN_STATUS_KEY = "isLoggedIn";
const TOKEN_EXPIRY_KEY = "token_expires_at";
const SESSION_WARNING_MINUTES = 5; // Show warning 5 minutes before expiry
const TOKEN_REFRESH_INTERVAL = 15 * 60 * 1000; // Check token every 15 minutes

// Global variables for timers
let tokenValidationTimer = null;
let logoutWarningTimer = null;
let autoLogoutTimer = null;
let logoutCallbacks = [];

// Configure axios defaults
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = "application/json";

// Add request interceptor to include token in all requests
axios.interceptors.request.use(
	(config) => {
		const token = getStoredToken();
		if (token) {
			config.headers.Authorization = `Bearer ${token}`;
		}
		return config;
	},
	(error) => {
		return Promise.reject(error);
	}
);

// Add response interceptor to handle token expiration
axios.interceptors.response.use(
	(response) => response,
	(error) => {
		if (error.response?.status === 401) {
			// Token expired or invalid, trigger logout
			performLogout();
		}
		return Promise.reject(error);
	}
);

// Token storage functions
const getStoredToken = () => localStorage.getItem(TOKEN_STORAGE_KEY);
const getStoredUser = () => {
	const user = localStorage.getItem(USER_STORAGE_KEY);
	return user ? JSON.parse(user) : null;
};
const getTokenExpiry = () => {
	const expiry = localStorage.getItem(TOKEN_EXPIRY_KEY);
	return expiry ? parseInt(expiry) : null;
};

// Store authentication data
const storeAuthData = (token, user, expiresAt) => {
	localStorage.setItem(TOKEN_STORAGE_KEY, token);
	localStorage.setItem(USER_STORAGE_KEY, JSON.stringify(user));
	localStorage.setItem(LOGIN_STATUS_KEY, "true");
	localStorage.setItem(TOKEN_EXPIRY_KEY, expiresAt.toString());
};

// Clear authentication data
const clearAuthData = () => {
	localStorage.removeItem(TOKEN_STORAGE_KEY);
	localStorage.removeItem(USER_STORAGE_KEY);
	localStorage.removeItem(LOGIN_STATUS_KEY);
	localStorage.removeItem(TOKEN_EXPIRY_KEY);
};

// Clear all timers
const clearAllTimers = () => {
	if (tokenValidationTimer) {
		clearInterval(tokenValidationTimer);
		tokenValidationTimer = null;
	}
	if (logoutWarningTimer) {
		clearTimeout(logoutWarningTimer);
		logoutWarningTimer = null;
	}
	if (autoLogoutTimer) {
		clearTimeout(autoLogoutTimer);
		autoLogoutTimer = null;
	}
};

// Perform logout and notify callbacks
const performLogout = () => {
	clearAllTimers();
	clearAuthData();

	// Notify all registered logout callbacks
	logoutCallbacks.forEach((callback) => {
		try {
			callback();
		} catch (error) {
			console.error("Error in logout callback:", error);
		}
	});
};

// Check if token is expired
const isTokenExpired = () => {
	const expiry = getTokenExpiry();
	if (!expiry) return true;

	return Date.now() >= expiry * 1000;
};

// Setup session timers
const setupSessionTimers = (expiresAt) => {
	clearAllTimers();

	const currentTime = Date.now();
	const expiryTime = expiresAt * 1000;
	const timeUntilExpiry = expiryTime - currentTime;
	const warningTime = timeUntilExpiry - SESSION_WARNING_MINUTES * 60 * 1000;

	// Set warning timer (5 minutes before expiry)
	if (warningTime > 0) {
		logoutWarningTimer = setTimeout(() => {
			notifySessionExpiring();
		}, warningTime);
	}

	// Set automatic logout timer
	if (timeUntilExpiry > 0) {
		autoLogoutTimer = setTimeout(() => {
			performLogout();
			notifySessionExpired();
		}, timeUntilExpiry);
	}

	// Set periodic token validation
	tokenValidationTimer = setInterval(() => {
		validateTokenWithServer();
	}, TOKEN_REFRESH_INTERVAL);
};

// Notify about session expiring
const notifySessionExpiring = () => {
	const event = new CustomEvent("sessionExpiring", {
		detail: { minutesLeft: SESSION_WARNING_MINUTES },
	});
	window.dispatchEvent(event);
};

// Notify about session expired
const notifySessionExpired = () => {
	const event = new CustomEvent("sessionExpired", {
		detail: { reason: "Token expired" },
	});
	window.dispatchEvent(event);
};

// Validate token with server
const validateTokenWithServer = async () => {
	try {
		if (!getStoredToken()) return false;

		const response = await axios.post(`${API_URL}validate-token`);

		if (response.data.status === "success") {
			// Update token expiry if provided
			if (response.data.token_expires_at) {
				localStorage.setItem(
					TOKEN_EXPIRY_KEY,
					response.data.token_expires_at.toString()
				);
				setupSessionTimers(response.data.token_expires_at);
			}
			return true;
		} else {
			performLogout();
			return false;
		}
	} catch (error) {
		console.error("Token validation failed:", error);
		if (error.response?.status === 401) {
			performLogout();
		}
		return false;
	}
};

export const login = async (data) => {
	try {
		const response = await axios.post(`${API_URL}login`, data);

		// Handle successful login
		if (response.data.status === "success") {
			const { token, token_expires_at, user } = response.data;

			// Store authentication data
			storeAuthData(token, user, token_expires_at);

			// Setup session management timers
			setupSessionTimers(token_expires_at);

			return {
				status: 200,
				data: response.data,
			};
		}

		return { status: response.status, data: response.data };
	} catch (error) {
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
		const response = await axios.post(`${API_URL}registerCustomer`, data);
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

export const logout = async () => {
	try {
		const token = getStoredToken();
		if (token) {
			// Try to logout from server
			try {
				await axios.post(`${API_URL}logout`);
			} catch (error) {
				console.error("Server logout failed:", error);
				// Continue with local logout even if server fails
			}
		}

		// Always perform local logout
		performLogout();

		return Promise.resolve({
			status: 200,
			message: "Logged out successfully",
		});
	} catch (error) {
		// Even if there's an error, perform local logout
		performLogout();
		return Promise.resolve({ status: 200, message: "Logged out locally" });
	}
};

export const getCurrentUser = () => {
	if (!isAuthenticated()) return null;
	return getStoredUser();
};

export const isAuthenticated = () => {
	const token = getStoredToken();
	const loginStatus = localStorage.getItem(LOGIN_STATUS_KEY);

	if (!token || loginStatus !== "true") {
		return false;
	}

	// Check if token is expired
	if (isTokenExpired()) {
		performLogout();
		return false;
	}

	return true;
};

// Session management functions
export const extendSession = async () => {
	try {
		const response = await axios.post(`${API_URL}validate-token`);
		if (
			response.data.status === "success" &&
			response.data.token_expires_at
		) {
			localStorage.setItem(
				TOKEN_EXPIRY_KEY,
				response.data.token_expires_at.toString()
			);
			setupSessionTimers(response.data.token_expires_at);
			return true;
		}
		return false;
	} catch (error) {
		console.error("Session extension failed:", error);
		return false;
	}
};

export const getTimeUntilExpiry = () => {
	const expiry = getTokenExpiry();
	if (!expiry) return 0;

	const timeLeft = expiry * 1000 - Date.now();
	return Math.max(0, Math.floor(timeLeft / 1000)); // Return seconds
};

export const addLogoutCallback = (callback) => {
	logoutCallbacks.push(callback);

	// Return cleanup function
	return () => {
		const index = logoutCallbacks.indexOf(callback);
		if (index > -1) {
			logoutCallbacks.splice(index, 1);
		}
	};
};

// Initialize session management when the module loads
export const initializeSession = () => {
	const token = getStoredToken();
	const expiry = getTokenExpiry();

	if (token && expiry && !isTokenExpired()) {
		setupSessionTimers(expiry);

		// Validate token immediately on initialization
		validateTokenWithServer();
	} else if (token) {
		// Token exists but is expired, clean up
		performLogout();
	}
};

// Auto-initialize when module loads
if (typeof window !== "undefined") {
	// Check if we're in a browser environment
	initializeSession();

	// Handle page visibility change to validate token when user returns
	document.addEventListener("visibilitychange", () => {
		if (!document.hidden && isAuthenticated()) {
			validateTokenWithServer();
		}
	});
}
