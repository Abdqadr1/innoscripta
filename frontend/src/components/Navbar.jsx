import { useDispatch, useSelector } from "react-redux";
import { logout as authLogout } from "../reducers/authSlice";
import React, { useState, useEffect, useRef } from "react";
import { Link, useNavigate } from "react-router-dom";

const Navbar = () => {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const dropdownRef = useRef(null); 

  const { user, token } = useSelector((state) => state.auth);

  const handleLogout = () => {
    dispatch(authLogout());
    navigate("/login");
  };

  const handleSettingsClick = () => {
    navigate("/settings");
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <nav className="bg-blue-600 text-white shadow-md">
      <div className="container mx-auto px-4 py-3 flex items-center justify-between">
        <Link to="/" className="text-lg font-bold hover:text-blue-200 text-white">
          News Aggregator
        </Link>
        <div className="relative">
          {token ? (
            <div className="flex items-center space-x-4">
              <div className="relative" ref={dropdownRef}>
                <span
                  onClick={() => setDropdownOpen(!dropdownOpen)}
                  className="flex items-center cursor-pointer text-white font-medium hover:text-blue-300"
                >
                  {user?.name}
                  <svg
                    className="w-4 h-4 ml-2"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2}
                      d="M19 9l-7 7-7-7"
                    />
                  </svg>
                </span>
                {dropdownOpen && (
                  <div className="absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
                    <button
                      onClick={handleSettingsClick}
                      className="block w-full px-4 py-2 text-left text-sm hover:bg-gray-200"
                    >
                      Settings
                    </button>
                    <button
                      onClick={handleLogout}
                      className="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-200"
                    >
                      Logout
                    </button>
                  </div>
                )}
              </div>
            </div>
          ) : (
            <div className="space-x-4">
              <Link to="/login" className="hover:text-blue-500 text-white">
                Login
              </Link>
              <Link
                to="/register"
                className="hover:text-blue-500 text-white"
              >
                Register
              </Link>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
