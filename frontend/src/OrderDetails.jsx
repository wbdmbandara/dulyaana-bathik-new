import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import OrderDetails from "./components/cart/order-details";
import Footer from "./components/common/Footer";

function OrderDetailsPage() {
	return (
		<>
			<title>Dulyaana Bathik - Order Details</title>
			<Header activeMenu="cart" />
			<PageTitle title="Order Details" />
			<OrderDetails />
			<Footer />
		</>
	);
}

export default OrderDetailsPage;
