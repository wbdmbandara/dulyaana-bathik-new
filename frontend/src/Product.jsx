import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import ProductDetails from "./components/common/ProductDetails";
import Footer from "./components/common/Footer";

const Product = () => {
	return (
		<div>
			<title>Dulyaana Bathik - Product</title>
			<Header />
			<PageTitle title="Product Details" />
			<ProductDetails />
			<Footer />
		</div>
	);
};

export default Product;
