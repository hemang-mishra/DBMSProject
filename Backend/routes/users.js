const express = require('express');
const { getAllUsers, addUser } = require('../db/users');
const router = express.Router();

router.get('/', async (req, res) => {
  try {
    const users = await getAllUsers();
    res.json(users);
  } catch (error) {
    res.status(500).send('Error fetching users');
  }
});

router.post('/', async (req, res) => {
  const { name, email } = req.body;
  try {
    await addUser(name, email);
    res.status(201).send('User added');
  } catch (error) {
    res.status(500).send('Error adding user');
  }
});

module.exports = router;
