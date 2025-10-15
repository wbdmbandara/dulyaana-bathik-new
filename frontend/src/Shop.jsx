import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import FilterProduct from "./components/shop/FilterProduct";
import ProductsList from "./components/shop/ProductsList";
import ShopHeader from "./components/shop/ShopHeader";
import Footer from "./components/common/Footer";

const Shop = () => {
  return (
    <div>
        <title>Dulyaana Bathik - Shop</title>
        <Header activeMenu="shop" />
        <PageTitle title="Shop" />
        <div className="container">
        <div className="row">
          <FilterProduct />
          <div className="col-lg-8">
              <ShopHeader />
              <ProductsList />
            </div>
          </div>
        </div>
        <Footer />
    </div>
  );
};

export default Shop