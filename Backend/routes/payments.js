const express = require('express');
const { insertPayment } = require('../db/payments');
const router = express.Router();

// API to insert a new payment
router.post('/add', async (req, res) => {
  const { id, method, date, p_status } = req.body;

  // Check for required fields
  if (!id || !method || !date || !p_status) {
    return res.status(400).send('All payment fields are required');
  }

  try {
    const result = await insertPayment(id, method, date, p_status);
    res.json({ message: 'Payment added successfully', result });
  } catch (error) {
    res.status(500).send('Error adding payment');
  }
});

module.exports = router;
