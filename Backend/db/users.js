const db = require('./dbConnection');


const addUserWithDetails = (user_id, username, isConsumer, password) => {
  return new Promise((resolve, reject) => {
    db.query('INSERT INTO user (user_id, username, isConsumer, password) VALUES (?, ?, ?, ?)', [user_id, username, isConsumer, password], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};

const getUserByUsernameAndPassword = (username, password) => {
  return new Promise((resolve, reject) => {
    db.query('SELECT * FROM user WHERE username = ? AND password = ?', [username, password], (err, results) => {
      if (err) return reject(err);
      resolve(results);
    });
  });
};



module.exports = { addUserWithDetails, getUserByUsernameAndPassword };
