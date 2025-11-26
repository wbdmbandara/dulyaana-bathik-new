import React, { useEffect, useState } from "react";
import {
	extendSession,
	addLogoutCallback,
	getTimeUntilExpiry,
} from "../../services/AuthService";

const SessionManager = ({ children, onSessionExpired, onSessionExpiring }) => {
	const [showWarning, setShowWarning] = useState(false);
	const [timeLeft, setTimeLeft] = useState(0);

	useEffect(() => {
		let warningUpdateInterval;

		const handleSessionExpiring = (event) => {
			setShowWarning(true);
			setTimeLeft(event.detail.minutesLeft * 60); // Convert to seconds

			// Update countdown every second
			warningUpdateInterval = setInterval(() => {
				const remaining = getTimeUntilExpiry();
				setTimeLeft(remaining);

				if (remaining <= 0) {
					clearInterval(warningUpdateInterval);
					setShowWarning(false);
				}
			}, 1000);

			if (onSessionExpiring) {
				onSessionExpiring(event.detail.minutesLeft);
			}
		};

		const handleSessionExpired = (event) => {
			setShowWarning(false);
			if (warningUpdateInterval) {
				clearInterval(warningUpdateInterval);
			}

			if (onSessionExpired) {
				onSessionExpired(event.detail.reason);
			} else {
				// Default behavior: show alert and redirect
				alert(
					"Your session has expired. You will be redirected to the login page."
				);
				window.location.href = "/login";
			}
		};

		// Add event listeners
		window.addEventListener("sessionExpiring", handleSessionExpiring);
		window.addEventListener("sessionExpired", handleSessionExpired);

		// Add logout callback
		const removeLogoutCallback = addLogoutCallback(() => {
			setShowWarning(false);
			if (warningUpdateInterval) {
				clearInterval(warningUpdateInterval);
			}
		});

		// Cleanup
		return () => {
			window.removeEventListener(
				"sessionExpiring",
				handleSessionExpiring
			);
			window.removeEventListener("sessionExpired", handleSessionExpired);
			removeLogoutCallback();
			if (warningUpdateInterval) {
				clearInterval(warningUpdateInterval);
			}
		};
	}, [onSessionExpired, onSessionExpiring]);

	const handleExtendSession = async () => {
		const success = await extendSession();
		if (success) {
			setShowWarning(false);
		} else {
			alert("Failed to extend session. Please log in again.");
		}
	};

	const handleLogoutNow = () => {
		setShowWarning(false);
		window.location.href = "/logout";
	};

	const formatTime = (seconds) => {
		const minutes = Math.floor(seconds / 60);
		const remainingSeconds = seconds % 60;
		return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
	};

	return (
		<>
			{children}

			{showWarning && (
				<div
					style={{
						position: "fixed",
						top: 0,
						left: 0,
						right: 0,
						bottom: 0,
						backgroundColor: "rgba(0, 0, 0, 0.5)",
						display: "flex",
						alignItems: "center",
						justifyContent: "center",
						zIndex: 9999,
					}}
				>
					<div
						style={{
							backgroundColor: "white",
							padding: "20px",
							borderRadius: "8px",
							boxShadow: "0 4px 6px rgba(0, 0, 0, 0.1)",
							maxWidth: "400px",
							textAlign: "center",
						}}
					>
						<h3 style={{ color: "#f59e0b", marginBottom: "16px" }}>
							⚠️ Session Expiring Soon
						</h3>
						<p style={{ marginBottom: "16px" }}>
							Your session will expire in{" "}
							<strong>{formatTime(timeLeft)}</strong>. Would you
							like to extend your session?
						</p>
						<div
							style={{
								display: "flex",
								gap: "10px",
								justifyContent: "center",
							}}
						>
							<button
								onClick={handleExtendSession}
								style={{
									backgroundColor: "#10b981",
									color: "white",
									border: "none",
									padding: "8px 16px",
									borderRadius: "4px",
									cursor: "pointer",
								}}
							>
								Extend Session
							</button>
							<button
								onClick={handleLogoutNow}
								style={{
									backgroundColor: "#ef4444",
									color: "white",
									border: "none",
									padding: "8px 16px",
									borderRadius: "4px",
									cursor: "pointer",
								}}
							>
								Logout Now
							</button>
						</div>
					</div>
				</div>
			)}
		</>
	);
};

export default SessionManager;
