import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import logo from "./logo.svg";
import "./App.css";

import Home from "./Home";
import About from "./About";
import Shop from "./Shop";
import Product from "./Product";
import Login from "./Login";
import Cart from "./Cart";
import Checkout from "./Checkout";
import Register from "./Register";
import Profile from "./user/Profile";
import Logout from "./Logout";
import SessionManager from "./components/common/SessionManager";
import { SnackbarProvider } from "./context/SnackbarContext";

function App() {
	return (
		<>
			<BrowserRouter>
				<SessionManager>
					<SnackbarProvider>
						<Routes>
							<Route path="/" element={<Home />} />
							<Route path="/about" element={<About />} />
							<Route path="/login" element={<Login />} />
							<Route path="/register" element={<Register />} />
							<Route path="/shop" element={<Shop />} />
							<Route path="/cart" element={<Cart />} />
							<Route path="/checkout" element={<Checkout />} />
							<Route
								path="/product/:url"
								element={<Product url={window.location.pathname} />}
							/>
							<Route path="/profile" element={<Profile />} />
							<Route path="/logout" element={<Logout />} />
						</Routes>
					</SnackbarProvider>
				</SessionManager>
			</BrowserRouter>
		</>
	);
}

export default App;
