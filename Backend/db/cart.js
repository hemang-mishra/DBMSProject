const db = require('./dbConnection');

// Insert a new cart
const insertCart = (cart_id, total_price, active, created_date, u_id) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO cart (
        cart_id, total_price, active, created_date, u_id
      ) 
      VALUES (?, ?, ?, ?, ?);
    `;
    db.query(query, [cart_id, total_price, active, created_date, u_id], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  insertCart,
};
