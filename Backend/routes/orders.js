const express = require('express');
const { 
  getAllCropsWithRatings, 
  getCropsByNameWithRatings, 
  insertOrder, 
  getOrderDetails 
} = require('../db/orders');
const router = express.Router();

// API to get all crops with their ratings
router.get('/', async (req, res) => {
  try {
    const crops = await getAllCropsWithRatings();
    res.json(crops);
  } catch (error) {
    res.status(500).send('Error fetching crops');
  }
});

// API to search crops by name
router.get('/search', async (req, res) => {
  const { name } = req.query;
  if (!name) {
    return res.status(400).send('Crop name is required');
  }

  try {
    const crops = await getCropsByNameWithRatings(name);
    res.json(crops);
  } catch (error) {
    res.status(500).send('Error fetching crops');
  }
});

// API to insert a new order
router.post('/add', async (req, res) => {
  const { order_id, date, status, price, amount, addr_id, id, cart_id, u_id } = req.body;

  if (!order_id || !date || !status || !price || !amount || !addr_id || !id || !cart_id || !u_id) {
    return res.status(400).send('All order fields are required');
  }

  try {
    const result = await insertOrder(order_id, date, status, price, amount, addr_id, id, cart_id, u_id);
    res.json({ message: 'Order added successfully', result });
  } catch (error) {
    res.status(500).send('Error adding order');
  }
});

// API to get detailed order information
router.get('/details', async (req, res) => {
  try {
    const orderDetails = await getOrderDetails();
    res.json(orderDetails);
  } catch (error) {
    res.status(500).send('Error fetching order details');
  }
});

module.exports = router;
