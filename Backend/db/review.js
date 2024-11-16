const db = require('./dbConnection');

// Fetch all reviews for a specific crop
const getReviewsByCropId = (cropId) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT *
      FROM review
      WHERE c_id = ?;
    `;
    db.query(query, [cropId], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

// Fetch the average rating for a specific crop
const getAverageRatingByCropId = (cropId) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT AVG(rating) AS average_rating
      FROM review
      WHERE c_id = ?;
    `;
    db.query(query, [cropId], (err, results) => {
      if (err) return reject(err);
      resolve(results[0]); // Return the first (and only) result
    });
  });
};

// Fetch the count of reviews for a specific rating and crop
const getRatingCountByCropId = (cropId, rating) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT COUNT(*) AS rating_count
      FROM review
      WHERE c_id = ? AND rating = ?;
    `;
    db.query(query, [cropId, rating], (err, results) => {
      if (err) return reject(err);
      resolve(results[0]); // Return the first (and only) result
    });
  });
};

// Insert a new review
const insertReview = (r_id, date, comment, r_img_url, u_id, c_id, rating) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO review (
          r_id,
          date,
          comment,
          r_img_url,
          u_id,
          c_id,
          rating
      ) 
      VALUES (?, ?, ?, ?, ?, ?, ?);
    `;
    db.query(query, [r_id, date, comment, r_img_url, u_id, c_id, rating], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  getReviewsByCropId,
  getAverageRatingByCropId,
  getRatingCountByCropId,
  insertReview, // Export the new function
};
