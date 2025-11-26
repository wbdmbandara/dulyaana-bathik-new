import React, { useEffect, useState } from "react";
import { Navigate } from "react-router-dom";
import { logout } from "./services/AuthService";
import Header from "./components/common/Header";
import Footer from "./components/common/Footer";

function Logout() {
	const [isLoggingOut, setIsLoggingOut] = useState(true);

	useEffect(() => {
		const performLogout = async () => {
			try {
				await logout();
			} catch (error) {
				console.error("Logout error:", error);
			} finally {
				setIsLoggingOut(false);
			}
		};

		performLogout();
	}, []);

	if (isLoggingOut) {
		return (
			<div>
				<Header />
				<div>Logging out...</div>
				<Footer />
			</div>
		);
	}

	return <Navigate to="/login" />;
}

export default Logout;
