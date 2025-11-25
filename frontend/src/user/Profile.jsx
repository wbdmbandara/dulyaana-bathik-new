import React from "react";
import { Link } from "react-router-dom";

import Header from "../components/common/Header";
import PageTitle from "../components/common/PageTitle";
import ProfileData from "../components/user/Profile";
import Footer from "../components/common/Footer";

const Profile = ({ url }) => {
	return (
		<div>
			<Header activeMenu="home" />
			<PageTitle title="Profile" />
			<ProfileData />
			<Footer />
		</div>
	);
};

export default Profile;
