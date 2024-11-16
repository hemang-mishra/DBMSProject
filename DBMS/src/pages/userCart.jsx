import React from "react";
import "./Cart.css";

const CartPage = () => {
  return (
    <div>
      {/* Navbar */}
      <div className="navbar">
        <div className="logo">🍃</div>
        <div className="search-bar">
          <input type="text" placeholder="Search" />
        </div>
        <div className="icons">
          <span>🛒</span>
          <span>📦</span>
          <span>👤</span>
        </div>
      </div>

      {/* Container */}
      <div className="container">
        <h1>Raven's Cart</h1>

        {/* Cart Items */}
        <div className="cart-items">
          {/* Watermelon */}
          <div className="cart-item">
            <img
              src="https://via.placeholder.com/50"
              alt="Watermelon"
              className="item-img"
            />
            <div className="item-details">
              <h3>Watermelon</h3>
              <p className="seller">Rani Mausi</p>
              <p>
                Qty: <input type="number" defaultValue="2" className="qty-input" /> pc
              </p>
            </div>
            <div className="item-price">₹199</div>
          </div>

          {/* Tomatoes */}
          <div className="cart-item">
            <img
              src="https://via.placeholder.com/50"
              alt="Tomatoes"
              className="item-img"
            />
            <div className="item-details">
              <h3>Tomatoes</h3>
              <p className="seller">Rani Mausi</p>
              <p>
                Qty: <input type="number" defaultValue="2" className="qty-input" /> kg
              </p>
            </div>
            <div className="item-price">₹99</div>
          </div>

          {/* Beans */}
          <div className="cart-item">
            <img
              src="https://via.placeholder.com/50"
              alt="Beans"
              className="item-img"
            />
            <div className="item-details">
              <h3>Beans</h3>
              <p className="seller">Rani Mausi</p>
              <p>
                Qty: <input type="number" defaultValue="0.5" className="qty-input" /> kg
              </p>
            </div>
            <div className="item-price">₹20</div>
          </div>
        </div>

        {/* Price Details */}
        <div className="price-details">
          <h2>Price Details</h2>
          <ul>
            <li>Watermelon (2pc): ₹199</li>
            <li>Tomatoes (2kg): ₹99</li>
            <li>Beans (0.5kg): ₹20</li>
          </ul>
          <h3>Total: ₹318</h3>
          <button className="order-btn">Place Your Order</button>
        </div>
      </div>
    </div>
  );
};

export default CartPage;
