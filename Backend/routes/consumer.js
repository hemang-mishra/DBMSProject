const express = require('express');
const { insertFarmer, insertConsumer } = require('../db/consumer');
const router = express.Router();

// API to insert a new farmer
router.post('/farmer', async (req, res) => {
  const { f_id, address, name, contact } = req.body;
  if (!f_id || !address || !name || !contact) {
    return res.status(400).send('All farmer fields are required');
  }

  try {
    const result = await insertFarmer(f_id, address, name, contact);
    res.json({ message: 'Farmer added successfully', result });
  } catch (error) {
    res.status(500).send('Error inserting farmer');
  }
});

// API to insert a new consumer
router.post('/consumer', async (req, res) => {
  const { u_id, uname, u_contact } = req.body;
  if (!u_id || !uname || !u_contact) {
    return res.status(400).send('All consumer fields are required');
  }

  try {
    const result = await insertConsumer(u_id, uname, u_contact);
    res.json({ message: 'Consumer added successfully', result });
  } catch (error) {
    res.status(500).send('Error inserting consumer');
  }
});

module.exports = router;
