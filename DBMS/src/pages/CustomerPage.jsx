import React from 'react';


const CustomerPage = () => {
  const products = [
    { id: 1, name: "Wheat", price: "₹50/kg", seller: "Raju Bhaiya", rating: 4, image: "🌾" },
    { id: 2, name: "Tomato", price: "₹35/kg", seller: "Rani Mausi", rating: 3.5, image: "🍅" },
    { id: 3, name: "Watermelon", price: "₹100/pc", seller: "Raju Bhaiya", rating: 5, image: "🍉" }
  ];

  return (
    <div style={{ fontFamily: 'Arial, sans-serif', backgroundColor: '#f5f5f5', minHeight: '100vh' }}>
      {/* Header Section */}
      <header style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        padding: '10px 20px',
        backgroundColor: '#fff',
        borderBottom: '1px solid #ddd',
        boxShadow: '0px 2px 4px rgba(0, 0, 0, 0.1)'
      }}>
        <div style={{ fontSize: '24px', fontWeight: 'bold', display: 'flex', alignItems: 'center' }}>
          <span style={{
            fontSize: '30px',
            marginRight: '10px',
            color: '#28a745',
            display: 'inline-block'
          }}>🌱</span>
          Company Logo
        </div>
        <div style={{ flex: 1, marginLeft: '20px', marginRight: '20px' }}>
          <input 
            type="text" 
            placeholder="Search" 
            style={{
              width: '100%',
              padding: '10px',
              borderRadius: '20px',
              border: '1px solid #ddd',
              fontSize: '16px'
            }}
          />
        </div>
        <div style={{
          display: 'flex',
          alignItems: 'center',
          gap: '15px',
          fontSize: '20px'
        }}>
          <div>🛒 Cart</div>
          <div>📦 Orders</div>
          <div>👤 Profile</div>
        </div>
      </header>

      {/* Products Section */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))',
        gap: '20px',
        padding: '20px'
      }}>
        {products.map(product => (
          <div 
            key={product.id} 
            style={{
              backgroundColor: '#fff',
              padding: '15px',
              borderRadius: '10px',
              boxShadow: '0px 4px 8px rgba(0, 0, 0, 0.1)',
              textAlign: 'center',
              cursor: 'pointer',
              transition: 'transform 0.2s ease'
            }}
            onMouseOver={(e) => e.currentTarget.style.transform = 'scale(1.05)'}
            onMouseOut={(e) => e.currentTarget.style.transform = 'scale(1)'}
          >
            <div style={{
              fontSize: '50px',
              marginBottom: '10px'
            }}>{product.image}</div>
            <h3 style={{ fontSize: '20px', margin: '5px 0' }}>{product.name}</h3>
            <p style={{ margin: '5px 0', fontWeight: 'bold' }}>{product.price}</p>
            <p style={{ margin: '5px 0', color: '#555' }}>{product.seller}</p>
            <div style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '5px',
              fontSize: '16px',
              color: '#f39c12'
            }}>
              {"★".repeat(Math.floor(product.rating))}
              {product.rating % 1 !== 0 && "½"}
              <span style={{ color: '#000', marginLeft: '5px' }}>({product.rating})</span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default CustomerPage;
