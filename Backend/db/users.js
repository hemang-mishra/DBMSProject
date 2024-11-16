const db = require('./dbConnection');

const getAllUsers = () => {
  return new Promise((resolve, reject) => {
    db.query('SELECT * FROM users', (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

const addUser = (name, email) => {
  return new Promise((resolve, reject) => {
    db.query('INSERT INTO users (name, email) VALUES (?, ?)', [name, email], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

module.exports = { getAllUsers, addUser };
