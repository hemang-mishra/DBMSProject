const db = require('./dbConnection');

// Fetch all addresses for a specific user based on user ID
const getAddressesByUserId = (u_id) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT a.*
      FROM addresses a
      JOIN orders o ON a.addr_id = o.addr_id
      WHERE o.u_id = ?;
    `;
    db.query(query, [u_id], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

// Insert a new address
const insertAddress = (addr_id, city, addr_line_1, addr_line_2, state, pin_code) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO addresses (
          addr_id,
          city,
          addr_line_1,
          addr_line_2,
          state,
          pin_code
      ) 
      VALUES (?, ?, ?, ?, ?, ?);
    `;
    db.query(query, [addr_id, city, addr_line_1, addr_line_2, state, pin_code], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  getAddressesByUserId,
  insertAddress,
};
