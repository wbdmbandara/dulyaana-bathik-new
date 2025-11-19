import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import Cart from "./components/cart/cart";
import Footer from "./components/common/Footer";

function CartPage() {
    return (
        <>
            <title>Dulyaana Bathik - Cart</title>
            <Header activeMenu="cart"/>
            <PageTitle title="Cart" />
            <Cart />
            <Footer />
        </>
    );
}

export default CartPage;
