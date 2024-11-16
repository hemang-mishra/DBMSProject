const db = require('./dbConnection');

// Insert a new payment
const insertPayment = (id, method, date, p_status) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO payments (
        id, method, date, p_status
      ) 
      VALUES (?, ?, ?, ?);
    `;
    db.query(query, [id, method, date, p_status], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  insertPayment,
};
