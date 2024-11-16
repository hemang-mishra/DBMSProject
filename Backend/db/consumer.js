const db = require('./dbConnection');

// Insert a new farmer
const insertFarmer = (f_id, address, name, contact) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO farmer (
          f_id,
          address,
          name,
          contact
      ) 
      VALUES (?, ?, ?, ?);
    `;
    db.query(query, [f_id, address, name, contact], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

// Insert a new consumer
const insertConsumer = (u_id, uname, u_contact) => {
  return new Promise((resolve, reject) => {
    const query = `
      INSERT INTO consumer (
          u_id,
          uname,
          u_contact
      ) 
      VALUES (?, ?, ?);
    `;
    db.query(query, [u_id, uname, u_contact], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = {
  insertFarmer,
  insertConsumer,
};
