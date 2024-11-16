const express = require('express');
const { getCropDetailsById } = require('../db/detail');
const router = express.Router();

// API to get crop details by ID
router.get('/:id', async (req, res) => {
  const cropId = req.params.id; // Get crop ID from URL parameters
  if (!cropId) {
    return res.status(400).send('Crop ID is required');
  }

  try {
    const cropDetails = await getCropDetailsById(cropId);
    if (cropDetails.length === 0) {
      return res.status(404).send('Crop not found');
    }
    res.json(cropDetails[0]); // Return the first (and only) result
  } catch (error) {
    res.status(500).send('Error fetching crop details');
  }
});

module.exports = router;
