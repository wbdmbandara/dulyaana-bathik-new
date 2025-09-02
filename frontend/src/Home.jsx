import React from "react";
import { Link } from "react-router-dom";
import HomeSlider from "./components/common/HomeSlider";
import PromoCards from "./components/common/PromoCards";
import CategoryCards from "./components/common/CategoryCards";
import BestSellers from "./components/common/BestSellers";
import ProductsList from "./components/common/ProductsList";

const Home = () => {
	return (
		<div>
			<HomeSlider />
			<PromoCards />
			<CategoryCards />
			<BestSellers />
			<ProductsList />
		</div>
	);
};

export default Home;
