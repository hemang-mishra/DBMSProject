import React from 'react';
import WheatImage from '../images/Wheat.jpeg'; // Import local image
import TomatoImage from '../images/Tomato.jpeg'
import WaterMelonImage from '../images/WaterMelon.jpeg'
import SujalImage from '../images/sujal.jpeg'
import BananaImage from '../images/Banana.jpeg'
import CarrotImage from '../images/Carrot.jpeg'
import CorianderImage from '../images/Coriander.jpeg'
import OnionImage from '../images/Onion.jpeg'
const CustomerPage = () => {
  // Updated product data with image URLs
  const products = [
    { id: 1, name: "Wheat", price: "₹50/kg", seller: "Raju Bhaiya", rating: 4, image: WheatImage },
    { id: 2, name: "Tomato", price: "₹35/kg", seller: "Rani Mausi", rating: 3.5, image: TomatoImage },
    { id: 3, name: "Watermelon", price: "₹100/pc", seller: "Raju Bhaiya", rating: 5, image: WaterMelonImage },
    { id: 4, name: "Sujal", price: "₹500", seller: "Devendra", rating: 4, image: SujalImage },
    { id: 5, name: "Onion", price: "₹50/kg", seller: "Shaurya",rating : 4,image:OnionImage},
    { id: 5, name: "Banana", price: "₹60/dozen", seller: "Hemang",rating : 4,image:BananaImage},
    { id: 5, name: "Coriander", price: "₹5/pc", seller: "Harsh",rating : 4,image:CorianderImage},
    { id: 5, name: "Carrot", price: "₹10/pc", seller: "Kesav",rating : 4,image: CarrotImage},
  ];

  return (
    <div
      style={{
        fontFamily: 'Arial, sans-serif',
        backgroundColor: '#f5f5f5',
        height: '100vh',
        width: '100vw',
        margin: '0',
        padding: '0',
        boxSizing: 'border-box',
        display: 'flex',
        flexDirection: 'column',
      }}
    >
      <header
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '10px 20px',
          backgroundColor: '#fff',
          borderBottom: '1px solid #ddd',
          boxShadow: '0px 2px 4px rgba(0, 0, 0, 0.1)',
          flexShrink: 0,
        }}
      >
        <div
          style={{
            fontSize: '24px',
            fontWeight: 'bold',
            display: 'flex',
            alignItems: 'center',
          }}
        >
          <span
            style={{
              fontSize: '30px',
              marginRight: '10px',
              color: '#28a745',
              display: 'inline-block',
            }}
          >
            🌱
          </span>
          Company Logo
        </div>
        <div
          style={{
            flex: 1,
            marginLeft: '20px',
            marginRight: '20px',
          }}
        >
          <input
            type="text"
            placeholder="Search"
            style={{
              width: '100%',
              padding: '10px',
              borderRadius: '20px',
              border: '1px solid #ddd',
              fontSize: '16px',
            }}
          />
        </div>
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '15px',
            fontSize: '20px',
          }}
        >
          <div>🛒 Cart</div>
          <div>📦 Orders</div>
          <div>👤 Profile</div>
        </div>
      </header>

      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))',
          gap: '20px',
          padding: '20px',
          flex: 1,
          margin: '0',
          alignItems: 'stretch',
        }}
      >
        {products.map((product) => (
          <div
            key={product.id}
            style={{
              backgroundColor: '#fff',
              padding: '15px',
              borderRadius: '10px',
              boxShadow: '0px 4px 8px rgba(0, 0, 0, 0.1)',
              textAlign: 'center',
              cursor: 'pointer',
              transition: 'transform 0.2s ease',
              display: 'flex',
              flexDirection: 'column',
              justifyContent: 'space-between',
              height: '300px',
            }}
            onMouseOver={(e) => (e.currentTarget.style.transform = 'scale(1.05)')}
            onMouseOut={(e) => (e.currentTarget.style.transform = 'scale(1)')}
          >
            <div
              style={{
                marginBottom: '10px',
              }}
            >
              <img
                src={product.image}
                alt={product.name}
                style={{
                  width: '100%',
                  height: '150px',
                  objectFit: 'cover',
                  borderRadius: '10px',
                }}
              />
            </div>
            <h3
              style={{
                fontSize: '20px',
                margin: '5px 0',
              }}
            >
              {product.name}
            </h3>
            <p
              style={{
                margin: '5px 0',
                fontWeight: 'bold',
              }}
            >
              {product.price}
            </p>
            <p
              style={{
                margin: '5px 0',
                color: '#555',
              }}
            >
              {product.seller}
            </p>
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '5px',
                fontSize: '16px',
                color: '#f39c12',
              }}
            >
              {"★".repeat(Math.floor(product.rating))}
              {product.rating % 1 !== 0 && "½"}
              <span
                style={{
                  color: '#000',
                  marginLeft: '5px',
                }}
              >
                ({product.rating})
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default CustomerPage;





