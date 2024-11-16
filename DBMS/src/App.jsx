import React from 'react';
import { useNavigate } from 'react-router-dom';

const App = () => {
  const navigate = useNavigate();

  const handleSelection = (userType) => {
    if (userType === 'farmer') {
      navigate('/login/farmer');
    } else if (userType === 'customer') {
      navigate('/login/customer');
    }
  };

  return (
    <div style={styles.container}>
      <h1>Welcome! Are you a Farmer or a Customer?</h1>
      <div style={styles.buttonContainer}>
        <button onClick={() => handleSelection('farmer')} style={styles.button}>
          Farmer
        </button>
        <button onClick={() => handleSelection('customer')} style={styles.button}>
          Customer
        </button>
      </div>
    </div>
  );
};

const styles = {
  container: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    marginTop: '50px',
  },
  buttonContainer: {
    display: 'flex',
    gap: '20px',
  },
  button: {
    padding: '10px 20px',
    fontSize: '16px',
    cursor: 'pointer',
  },
};

export default App;


