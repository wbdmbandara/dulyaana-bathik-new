import React from "react";
import { Link } from "react-router-dom";

import ResetPasswordForm from "./components/common/ResetPasswordForm";
import Header from "./components/common/Header";
import Footer from "./components/common/Footer";

function ResetPassword() {
	return (
		<div>
			<Header activeMenu="login" />
			<ResetPasswordForm />
			<Footer />
		</div>
	);
}

export default ResetPassword;
