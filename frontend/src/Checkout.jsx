import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import Checkout from "./components/cart/checkout";
import Footer from "./components/common/Footer";

function CheckoutPage() {
	return (
		<>
			<title>Dulyaana Bathik - Checkout</title>
			<Header activeMenu="checkout" />
			<PageTitle title="Checkout" />
			<Checkout />
			<Footer />
		</>
	);
}

export default CheckoutPage;
