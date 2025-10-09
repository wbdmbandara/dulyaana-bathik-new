import React from "react";
import { Link } from "react-router-dom";

import LoginForm from "./components/common/LoginForm";
import Header from "./components/common/Header";
import Footer from "./components/common/Footer";

function LoginPage() {
	return (
		<div>
			<Header activeMenu="login" />
			<LoginForm />
			<Footer />
		</div>
	);
}

export default LoginPage;
