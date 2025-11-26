import React, { useEffect, useState } from "react";
import { isAuthenticated, getCurrentUser } from "../../services/AuthService";

const ProtectedRoute = ({
	children,
	redirectTo = "/login",
	requiredRole = null,
}) => {
	const [isLoading, setIsLoading] = useState(true);
	const [isAuthorized, setIsAuthorized] = useState(false);

	useEffect(() => {
		const checkAuth = () => {
			const authenticated = isAuthenticated();

			if (!authenticated) {
				setIsAuthorized(false);
				setIsLoading(false);
				return;
			}

			// Check role if required
			if (requiredRole) {
				const user = getCurrentUser();
				const userRole = user?.role || "customer";

				if (userRole !== requiredRole) {
					setIsAuthorized(false);
					setIsLoading(false);
					return;
				}
			}

			setIsAuthorized(true);
			setIsLoading(false);
		};

		checkAuth();
	}, [requiredRole]);

	// Redirect if not authorized
	useEffect(() => {
		if (!isLoading && !isAuthorized) {
			window.location.href = redirectTo;
		}
	}, [isLoading, isAuthorized, redirectTo]);

	if (isLoading) {
		return (
			<div
				style={{
					display: "flex",
					justifyContent: "center",
					alignItems: "center",
					height: "100vh",
				}}
			>
				<div>Loading...</div>
			</div>
		);
	}

	return isAuthorized ? children : null;
};

export default ProtectedRoute;
