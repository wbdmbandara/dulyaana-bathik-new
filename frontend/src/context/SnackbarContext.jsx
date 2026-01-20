import React, {
	createContext,
	useContext,
	useState,
	useEffect,
	useCallback,
} from "react";

// 1. Create the Context
const SnackbarContext = createContext();

// 2. Custom Hook for easy usage
export const useSnackbar = () => useContext(SnackbarContext);

// 3. The Provider Component
export const SnackbarProvider = ({ children }) => {
	const [snack, setSnack] = useState({
		isOpen: false,
		message: "",
		type: "success", // 'success', 'error', or 'info'
		action: null, // Optional "Undo" function
	});

	// Auto-hide logic
	useEffect(() => {
		if (snack.isOpen) {
			const calculatedDuration = 4000 + snack.message.length * 50;
			const duration = Math.min(calculatedDuration, 10000);

			const timer = setTimeout(() => {
				setSnack((prev) => ({ ...prev, isOpen: false }));
			}, duration);
			return () => clearTimeout(timer);
		}
	}, [snack.isOpen, snack.message]);

	// Function to trigger the snackbar
	const showSnackbar = useCallback(
		(message, type = "success", action = null) => {
			setSnack({ isOpen: true, message, type, action });
		},
		[]
	);

	// Function to close it manually
	const hideSnackbar = () => {
		setSnack((prev) => ({ ...prev, isOpen: false }));
	};

	return (
		<SnackbarContext.Provider value={{ showSnackbar }}>
			{children}
			{/* Conditionally render the UI */}
			{snack.isOpen && (
				<SnackbarUI snack={snack} onClose={hideSnackbar} />
			)}
		</SnackbarContext.Provider>
	);
};

// 4. The UI Component (Internal Use)
const SnackbarUI = ({ snack, onClose }) => {
	// Configuration for different types
	const typeMap = {
		success: {
			// Green border for success
			backgroundColor: "#198754",
			text: "text-white",
			icon: <i className="bi bi-check-circle-fill fs-5 text-white"></i>,
		},
		error: {
			// Red border for error
			backgroundColor: "#dc3545",
			text: "text-white",
			icon: <i className="bi bi-x-circle-fill fs-5 text-white"></i>,
		},
		info: {
			// Blue border for info
			backgroundColor: "#0dcaf0",
			text: "text-black",
			icon: <i className="bi bi-info-circle-fill fs-5 text-black"></i>,
		},
	};

	const currentStyle = typeMap[snack.type] || typeMap.success;

	return (
		<div
			className={`position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 shadow rounded d-flex align-items-center gap-3 ${currentStyle.text}`}
			style={{
				zIndex: 1050,
				minWidth: "300px",
				maxWidth: "90%",
				backgroundColor: currentStyle.backgroundColor,
				borderLeft: "8px solid",
				borderColor: "var(--accent-color)",
				animation: "slideUp 0.3s ease-out",
			}}
		>
			<span className={currentStyle.text}>{currentStyle.icon}</span>

			<div
				className="flex-grow-1 fw-medium"
				style={{ fontSize: "0.95rem" }}
			>
				{snack.message}
			</div>

			{snack.action && (
				<button
					onClick={snack.action}
					className="btn btn-sm btn-link text-decoration-none fw-bold text-white text-uppercase p-0 me-2"
					style={{ fontSize: "0.85rem" }}
				>
					Undo
				</button>
			)}

			<button
				onClick={onClose}
				className="btn-close btn-close-white"
				aria-label="Close"
			></button>
		</div>
	);
};
