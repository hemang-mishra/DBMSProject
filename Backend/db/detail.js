const db = require('./dbConnection');

// Get details of a specific crop by ID
const getCropDetailsById = (cropId) => {
  return new Promise((resolve, reject) => {
    const query = `
      SELECT *
      FROM crop
      WHERE c_id = ?;
    `;
    db.query(query, [cropId], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = { getCropDetailsById };
