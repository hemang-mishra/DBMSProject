const express = require('express');
const { addUserWithDetails, getUserByUsernameAndPassword  } = require('../db/users');
const router = express.Router();

router.post('/add', async (req, res) => {
  try {
    const user = await addUserWithDetails(req.body.user_id, req.body.username, req.body.isConsumer, req.body.password);
    res.status(201).json(user);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

router.post('/login', async (req, res) => {
  try {
    const user = await getUserByUsernameAndPassword(req.body.username, req.body.password);
    if (user) {
      res.status(200).json(user);
    } else {
      res.status(401).json({ error: 'Invalid username or password' });
    }
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

module.exports = router;
