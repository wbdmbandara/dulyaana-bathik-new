import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import ProductDetails from "./components/common/ProductDetails";
import RelatedProducts from "./components/common/RelatedProducts";
import Footer from "./components/common/Footer";

const Product = ({ url }) => {
	return (
		<div>
			<Header activeMenu='home' />
			<ProductDetails url={url} />
			<RelatedProducts url={url} />
			<Footer />
		</div>
	);
};

export default Product;
