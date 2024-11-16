const express = require('express');
const { insertCart } = require('../db/cart');
const router = express.Router();

// API to insert a new cart
router.post('/add', async (req, res) => {
  const { cart_id, total_price, active, created_date, u_id } = req.body;

  // Check for required fields
  if (!cart_id || total_price === undefined || active === undefined || !created_date || !u_id) {
    return res.status(400).send('All cart fields are required');
  }

  try {
    const result = await insertCart(cart_id, total_price, active, created_date, u_id);
    res.json({ message: 'Cart added successfully', result });
  } catch (error) {
    res.status(500).send('Error adding cart');
  }
});

module.exports = router;
