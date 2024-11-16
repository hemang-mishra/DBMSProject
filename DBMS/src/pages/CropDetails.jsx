import React, { useState } from "react";
import { useNavigate } from 'react-router-dom';


const CropDetails = () => {
    const navigate = useNavigate();
  const [quantity, setQuantity] = useState(""); // To track the quantity input
  const [isAddingToCart, setIsAddingToCart] = useState(false); // To toggle input field
  const [reviews] = useState([
    { username: "User1", rating: 5, comment: "Awesome! The wheat is very good ❤️❤️" },
    { username: "User2", rating: 4, comment: "Good quality wheat at a fair price." },
    { username: "User3", rating: 3, comment: "Average quality, but fast delivery." },
  ]);

    const handleAddToCart = () => {
    if (!isAddingToCart) {
      setIsAddingToCart(true); // Enable input field
    } else {
        navigate('/user/cart');
      alert(`Added ${quantity} kg to the cart!`); // Mock action
      setIsAddingToCart(false); // Reset the button
      setQuantity(""); // Clear the quantity input
    }
  };

  return (
    <div
      style={{
        fontFamily: "Arial, sans-serif",
        height: "100vh", // Full screen height
        width: "100vw", // Full screen width
        display: "flex",
        flexDirection: "column",
        overflowY: "auto", // Ensure scrollability if content exceeds screen height
        backgroundColor: "#f5f5f5",
      }}
    >
      {/* Header */}
      <header
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          padding: "10px 20px",
          backgroundColor: "#fff",
          borderBottom: "1px solid #ddd",
          boxShadow: "0px 2px 4px rgba(0, 0, 0, 0.1)",
        }}
      >
        <span style={{ fontSize: "30px", color: "#28a745" }}>🌱</span>
        <input
          type="text"
          placeholder="Search"
          style={{
            flex: 1,
            margin: "0 20px",
            padding: "10px",
            borderRadius: "20px",
            border: "1px solid #ddd",
          }}
        />
        <div style={{ display: "flex", gap: "15px", fontSize: "20px" }}>
          <div>🛒 Cart</div>
          <div>📦 Orders</div>
          <div>👤 Profile</div>
        </div>
      </header>

      {/* Content Container */}
      <div style={{ flex: 1, padding: "20px", overflowY: "auto" }}>
        {/* Product Information */}
        <div style={{ display: "flex", gap: "20px", marginBottom: "30px" }}>
          <div style={{ flex: 1 }}>
            <div
              style={{
                fontSize: "100px",
                textAlign: "center",
                marginBottom: "10px",
              }}
            >
              🌾
            </div>
          </div>
          <div style={{ flex: 2 }}>
            <h1>Ramu Bhaiya's Wheat</h1>
            <p style={{ fontSize: "18px", fontWeight: "bold", color: "#333" }}>₹50/kg</p>
            <p style={{ fontSize: "16px", color: "#666" }}>Seller: Ramu Bhaiya</p>
            <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "20px" }}>
              <div style={{ fontSize: "20px", color: "#f39c12" }}>★★★★☆</div>
              <span>(4.2)</span>
            </div>
            {isAddingToCart ? (
              <div style={{ display: "flex", gap: "10px" }}>
                <input
                  type="number"
                  value={quantity}
                  onChange={(e) => setQuantity(e.target.value)}
                  placeholder="Quantity (kg)"
                  style={{
                    padding: "10px",
                    border: "1px solid #ddd",
                    borderRadius: "5px",
                    width: "100px",
                  }}
                />
                <button
                  onClick={handleAddToCart}
                  style={{
                    padding: "10px 20px",
                    backgroundColor: "#28a745",
                    color: "#fff",
                    border: "none",
                    borderRadius: "5px",
                    cursor: "pointer",
                  }}
                >
                  ✅ Confirm
                </button>
              </div>
            ) : (
              <button
                onClick={handleAddToCart}
                style={{
                  padding: "10px 20px",
                  backgroundColor: "#28a745",
                  color: "#fff",
                  border: "none",
                  borderRadius: "5px",
                  cursor: "pointer",
                }}
              >
                Add to Cart
              </button>
            )}
          </div>
        </div>

        {/* Ratings and Reviews Section */}
        <div style={{ display: "flex", gap: "20px" }}>
          {/* Ratings Summary */}
          <div style={{ flex: 1 }}>
            <h2>Ratings & Reviews</h2>
            <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "10px" }}>
              <div style={{ fontSize: "24px", fontWeight: "bold" }}>4.2</div>
              <div style={{ fontSize: "16px", color: "#f39c12" }}>★★★★☆</div>
            </div>
            <ul>
              {[5, 4, 3, 2, 1].map((star) => (
                <li key={star} style={{ display: "flex", alignItems: "center", gap: "10px" }}>
                  {star}★:{" "}
                  <div style={{ width: "200px", height: "10px", backgroundColor: "#ddd" }}>
                    <div
                      style={{
                        height: "100%",
                        width: `${star * 10}%`,
                        backgroundColor: "#28a745",
                      }}
                    ></div>
                  </div>
                </li>
              ))}
            </ul>
          </div>

          {/* Reviews Section */}
          <div style={{ flex: 1, backgroundColor: "#f9f9f9", padding: "10px", borderRadius: "5px" }}>
            <h3>User Reviews</h3>
            <div style={{ maxHeight: "300px", overflowY: "scroll", borderRadius: "5px" }}>
              {reviews.map((review, index) => (
                <div
                  key={index}
                  style={{
                    padding: "10px",
                    marginBottom: "10px",
                    backgroundColor: "#fff",
                    borderRadius: "5px",
                    boxShadow: "0px 2px 4px rgba(0, 0, 0, 0.1)",
                  }}
                >
                  <div style={{ fontWeight: "bold" }}>{review.username}</div>
                  <div style={{ color: "#f39c12" }}>{"★".repeat(review.rating)}</div>
                  <p>{review.comment}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CropDetails;
