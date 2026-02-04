import React from "react";
import { Link } from "react-router-dom";

import ForgotPasswordForm from "./components/common/ForgotPasswordForm";
import Header from "./components/common/Header";
import Footer from "./components/common/Footer";

function ForgotPassword() {
	return (
		<div>
			<Header activeMenu="login" />
			<ForgotPasswordForm />
			<Footer />
		</div>
	);
}

export default ForgotPassword;
