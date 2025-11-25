import React from "react";
import { Link, Navigate } from "react-router-dom";
import { logout } from "./services/AuthService";

function Logout() {
    logout();
    return <Navigate to="/login" />;
}

export default Logout;
