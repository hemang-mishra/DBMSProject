const db = require('./dbConnection');

// Get all crops with their farmer details and average ratings
const getAllCropsWithRatings = () => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT crop.*, farmer.name AS farmer_name
      FROM crop
      LEFT JOIN (
          SELECT c_id, AVG(rating) AS avg_rating
          FROM review
          GROUP BY c_id
      ) AS crop_ratings ON crop.c_id = crop_ratings.c_id
      LEFT JOIN farmer ON crop.f_id = farmer.f_id
      ORDER BY crop_ratings.avg_rating DESC;
    `;
    db.query(query, (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

// Get crops by partial name match with farmer details and average ratings
const getCropsByNameWithRatings = (cropName) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT crop.*, farmer.name AS farmer_name
      FROM crop
      LEFT JOIN (
          SELECT c_id, AVG(rating) AS avg_rating
          FROM review
          GROUP BY c_id
      ) AS crop_ratings ON crop.c_id = crop_ratings.c_id
      LEFT JOIN farmer ON crop.f_id = farmer.f_id
      WHERE crop.name LIKE ?
      ORDER BY crop_ratings.avg_rating DESC;
    `;
    db.query(query, [`%${cropName}%`], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

// Insert a new order
const insertOrder = (order_id, date, status, price, amount, addr_id, id, cart_id, u_id) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO orders (
          order_id, date, status, price, amount, addr_id, id, cart_id, u_id
      ) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
    `;
    db.query(
      query,
      [order_id, date, status, price, amount, addr_id, id, cart_id, u_id],
      (err, results) => {
        if (err) return reject(err);
        resolve(results);
      }
    );
  });
};

// Get detailed order information
const getOrderDetails = () => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT 
          o.order_id, o.date, o.status, o.price, o.amount,
          a.city, a.addr_line_1, a.addr_line_2,
          p.method AS payment_method, c.uname AS consumer_name, cr.name AS crop_name
      FROM 
          orders o
      JOIN 
          addresses a ON o.addr_id = a.addr_id
      JOIN 
          payments p ON o.id = p.id
      JOIN 
          consumer c ON o.u_id = c.u_id
      JOIN 
          crop cr ON o.c_id = cr.c_id;
    `;
    db.query(query, (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  getAllCropsWithRatings,
  getCropsByNameWithRatings,
  insertOrder,
  getOrderDetails,
};
