import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Navbar from "./components/Navbar";
import Login from "./components/Auth/Login";
import Feed from "./components/Articles/Feed";
import Search from "./components/Articles/Search";
import ProtectedRoute from "./components/ProtectedRoute";
import './App.css'
import Register from "./components/Auth/Register";
import Settings from "./components/Settings";

const App = () => (
  <Router>
    <Navbar />
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />

      <Route element={<ProtectedRoute />} >

          <Route path="/" element={<Feed />} />
          <Route path="/search" element={<Search />} />
          <Route path="/settings" element={<Settings />} />

      </Route>

    </Routes>
  </Router>
);

export default App;
