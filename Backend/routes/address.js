const express = require('express');
const { getAddressesByUserId, insertAddress } = require('../db/address');
const router = express.Router();

// API to fetch addresses for a specific user
router.get('/:u_id', async (req, res) => {
  const u_id = req.params.u_id;
  if (!u_id) {
    return res.status(400).send('User ID is required');
  }

  try {
    const addresses = await getAddressesByUserId(u_id);
    res.json(addresses);
  } catch (error) {
    res.status(500).send('Error fetching addresses');
  }
});

// API to insert a new address
router.post('/', async (req, res) => {
  const { addr_id, city, addr_line_1, addr_line_2, state, pin_code } = req.body;
  if (!addr_id || !city || !addr_line_1 || !state || !pin_code) {
    return res.status(400).send('All required address fields must be provided');
  }

  try {
    const result = await insertAddress(addr_id, city, addr_line_1, addr_line_2 || null, state, pin_code);
    res.json({ message: 'Address added successfully', result });
  } catch (error) {
    res.status(500).send('Error inserting address');
  }
});

module.exports = router;
