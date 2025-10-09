import React from "react";
import Header from "./components/common/Header";
import RegisterForm from "./components/common/RegisterForm";
import Footer from "./components/common/Footer";

function Register() {
	return (
		<div>
			<Header activeMenu="register" />
			<RegisterForm />
			<Footer />
		</div>
	);
}

export default Register;
