import React from "react";
import { Link } from "react-router-dom";

import Header from "./components/common/Header";
import PageTitle from "./components/common/PageTitle";
import ContactDetails from "./components/common/ContactDetails";
import Footer from "./components/common/Footer";

const Contact = () => {
	return (
		<div>
			<title>Dulyaana Bathik - Contact</title>
			<Header activeMenu="contact" />
			<PageTitle title="Contact" />
			<ContactDetails />
			<Footer />
		</div>
	);
};

export default Contact;
