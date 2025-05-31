// src/components/PrivateRoute.jsx

import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';

const PrivateRoute = ({ user }) => {
    // If 'user' object exists (meaning user is logged in), render the child routes
    // Otherwise, redirect to the login page
    return user ? <Outlet /> : <Navigate to="/login" replace />;
};

export default PrivateRoute;