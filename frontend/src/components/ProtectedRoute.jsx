import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';
import { useSelector } from 'react-redux';

const ProtectedRoute = () => {
  const { token:isAuthenticated } = useSelector((state) => state.auth);

  return (
    isAuthenticated 
    ? <Outlet /> 
    : <Navigate to="/login" />
  )

};


export default ProtectedRoute;
