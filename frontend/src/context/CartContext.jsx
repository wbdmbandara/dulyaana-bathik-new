import React, { createContext, useContext, useState, useCallback } from "react";
import { API_URL } from "../config";

// 1. Create the Context
const CartContext = createContext();

// 2. Custom Hook for easy usage
export const useCart = () => useContext(CartContext);

// 3. The Provider Component
export const CartProvider = ({ children }) => {
	const [cartItems, setCartItems] = useState([]);
	const [cartCount, setCartCount] = useState(0);

	// Function to fetch cart items
	const fetchCartItems = useCallback(async () => {
		const customerID = JSON.parse(localStorage.getItem("user"))?.id;
		if (!customerID) {
			setCartItems([]);
			setCartCount(0);
			return;
		}

		try {
			const response = await fetch(`${API_URL}cart`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${localStorage.getItem(
						"auth_token"
					)}`,
				},
				body: JSON.stringify({
					customer_id: customerID,
				}),
			});

			if (!response.ok) {
				throw new Error("Failed to fetch cart items");
			}

			const data = await response.json();
			setCartItems(data.cart_items || []);
			setCartCount(data.cart_items?.length || 0);
		} catch (error) {
			console.error("Error fetching cart items:", error);
			setCartItems([]);
			setCartCount(0);
		}
	}, []);

	// Function to refresh cart (called after add/remove operations)
	const refreshCart = useCallback(() => {
		fetchCartItems();
	}, [fetchCartItems]);

	return (
		<CartContext.Provider
			value={{
				cartItems,
				cartCount,
				fetchCartItems,
				refreshCart,
				setCartItems,
			}}
		>
			{children}
		</CartContext.Provider>
	);
};
