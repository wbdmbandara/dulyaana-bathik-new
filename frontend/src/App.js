import React, { useEffect, useState } from "react";
import axios from "axios";
import { API_URL, BACKEND_URL, formatNumber, formatCurrency } from "./config";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import logo from "./logo.svg";
import "./App.css";

import Home from "./Home";
import About from "./About";
import Contact from "./Contact";
import Shop from "./Shop";
import Product from "./Product";
import Login from "./Login";
import ForgotPassword from "./ForgotPassword";
import ResetPassword from "./ResetPassword";
import Cart from "./Cart";
import Checkout from "./Checkout";
import OrderDetails from "./OrderDetails";
import Register from "./Register";
import Profile from "./user/Profile";
import Logout from "./Logout";
import SessionManager from "./components/common/SessionManager";
import { SnackbarProvider } from "./context/SnackbarContext";
import { CartProvider } from "./context/CartContext";

function App() {
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchTheme = async () => {
			try {
				// 1. Fetch data from Laravel
				const response = await axios.get(
					API_URL + "theme-settings"
				);
				const theme = response.data;

				// 2. Update CSS Variables directly on the document root
				const root = document.documentElement;

				root.style.setProperty("--primary-color", theme.primary_color);
				root.style.setProperty("--background-color", theme.background_color);
				root.style.setProperty("--default-color", theme.default_color);
				root.style.setProperty("--heading-color", theme.heading_color);
				root.style.setProperty("--accent-color", theme.accent_color);
				root.style.setProperty("--surface-color", theme.surface_color);
				root.style.setProperty("--contrast-color", theme.contrast_color);
				root.style.setProperty("--nav-color", theme.nav_color);
				root.style.setProperty("--nav-hover-color", theme.nav_hover_color);
				root.style.setProperty("--nav-mobile-background-color", theme.nav_mobile_background_color);
				root.style.setProperty("--nav-dropdown-background-color", theme.nav_dropdown_background_color);
				root.style.setProperty("--nav-dropdown-color", theme.nav_dropdown_color);
				root.style.setProperty("--nav-dropdown-hover-color", theme.nav_dropdown_hover_color);

				// Save to localStorage to prevent "flash" on next reload
				localStorage.setItem("site_theme", JSON.stringify(theme));
			} catch (error) {
				console.error("Failed to load theme", error);
			} finally {
				setLoading(false);
			}
		};

		// Load from localStorage first if available
		const savedTheme = localStorage.getItem("site_theme");
		if (savedTheme) {
			const theme = JSON.parse(savedTheme);
			document.documentElement.style.setProperty("--primary-color", theme.primary_color);
			document.documentElement.style.setProperty("--background-color", theme.background_color);
			document.documentElement.style.setProperty("--default-color", theme.default_color);
			document.documentElement.style.setProperty("--heading-color", theme.heading_color);
			document.documentElement.style.setProperty("--accent-color", theme.accent_color);
			document.documentElement.style.setProperty("--surface-color", theme.surface_color);
			document.documentElement.style.setProperty("--contrast-color", theme.contrast_color);
			document.documentElement.style.setProperty("--nav-color", theme.nav_color);
			document.documentElement.style.setProperty("--nav-hover-color", theme.nav_hover_color);
			document.documentElement.style.setProperty("--nav-mobile-background-color", theme.nav_mobile_background_color);
			document.documentElement.style.setProperty("--nav-dropdown-background-color", theme.nav_dropdown_background_color);
			document.documentElement.style.setProperty("--nav-dropdown-color", theme.nav_dropdown_color);
			document.documentElement.style.setProperty("--nav-dropdown-hover-color", theme.nav_dropdown_hover_color);
		}

		fetchTheme();
	}, []);

	if (loading && !localStorage.getItem("site_theme"))
		return <div>Loading styles...</div>;

	return (
		<>
			<BrowserRouter>
				<SessionManager>
					<SnackbarProvider>
						<CartProvider>
							<Routes>
								<Route path="/" element={<Home />} />
								<Route path="/about" element={<About />} />
								<Route path="/contact" element={<Contact />} />
								<Route path="/login" element={<Login />} />
								<Route path="/register" element={<Register />} />
								<Route path="/shop" element={<Shop />} />
								<Route path="/cart" element={<Cart />} />
								<Route path="/checkout" element={<Checkout />} />
								<Route path="/order-details" element={<OrderDetails />} />
								<Route path="/forgot-password" element={<ForgotPassword />} />
								<Route path="/reset-password" element={<ResetPassword />} />
								<Route
									path="/product/:url" 
									element={<Product url={window.location.pathname} />}
								/>{" "}
								<Route path="/profile" element={<Profile />} />
								<Route path="/logout" element={<Logout />} />
							</Routes>
						</CartProvider>
					</SnackbarProvider>
				</SessionManager>
			</BrowserRouter>
		</>
	);
}

export default App;
