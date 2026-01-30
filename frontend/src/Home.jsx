import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import HomeSlider from "./components/common/HomeSlider";
import PromoCards from "./components/common/PromoCards";
import CategoryCards from "./components/common/CategoryCards";
import BestSellers from "./components/common/BestSellers";
import ProductsList from "./components/common/ProductsList";
import Footer from "./components/common/Footer";

const Home = () => {
	return (
		<div>
			<title>Dulyaana Bathik - Home</title>
            <Header activeMenu="home"/>
			<HomeSlider />
			<PromoCards />
			<BestSellers />
			<ProductsList />
			<Footer />
		</div>
	);
};

export default Home;
