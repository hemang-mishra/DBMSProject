import React from 'react';
import { useNavigate } from 'react-router-dom'; // Import useNavigate
import "./LoginPage.css";

const CustomerLogin = () => {
  const navigate = useNavigate(); // Initialize navigate

  const handleLogin = () => {
    navigate('/customer-page'); // Navigate to CustomerPage
  };

  return (
    <div className="login-container">
      <header className="login-header">
        <img src="/path/to/logo.png" alt="Company Logo" className="login-logo" />
        <h1>Company Name</h1>
      </header>
      <div className="login-box">
        <h2>LOGIN</h2>
        <div className="input-group">
          <input type="text" placeholder="Username" className="login-input" />
          <span className="icon">👤</span>
        </div>
        <div className="input-group">
          <input type="password" placeholder="Password" className="login-input" />
          <span className="icon">🔑</span>
        </div>
        <div className="login-options">
          <label>
            <input type="checkbox" /> Remember Me
          </label>
          <a href="/forgot-password" className="forgot-password">Forgot Password?</a>
        </div>
        <button onClick={handleLogin} className="login-button">Login</button> {/* Add onClick */}
      </div>
    </div>
  );
};

export default CustomerLogin;

