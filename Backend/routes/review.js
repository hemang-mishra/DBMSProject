const express = require('express');
const { insertReview, getReviewsByCropId, getAverageRatingByCropId, getRatingCountByCropId } = require('../db/review');
const router = express.Router();

// API to fetch all reviews for a specific crop
router.get('/:id', async (req, res) => {
  const cropId = req.params.id;
  if (!cropId) {
    return res.status(400).send('Crop ID is required');
  }

  try {
    const reviews = await getReviewsByCropId(cropId);
    res.json(reviews);
  } catch (error) {
    res.status(500).send('Error fetching reviews');
  }
});

// API to fetch the average rating for a specific crop
router.get('/:id/average', async (req, res) => {
  const cropId = req.params.id;
  if (!cropId) {
    return res.status(400).send('Crop ID is required');
  }

  try {
    const averageRating = await getAverageRatingByCropId(cropId);
    res.json(averageRating);
  } catch (error) {
    res.status(500).send('Error fetching average rating');
  }
});

// API to fetch the count of reviews for a specific rating level (1-5) for a specific crop
router.get('/:id/ratings/:rating', async (req, res) => {
  const cropId = req.params.id;
  const rating = parseInt(req.params.rating);
  if (!cropId || isNaN(rating) || rating < 1 || rating > 5) {
    return res.status(400).send('Valid Crop ID and rating (1-5) are required');
  }

  try {
    const ratingCount = await getRatingCountByCropId(cropId, rating);
    res.json(ratingCount);
  } catch (error) {
    res.status(500).send('Error fetching rating count');
  }
});

// API to insert a new review
router.post('/', async (req, res) => {
  const { r_id, date, comment, r_img_url, u_id, c_id, rating } = req.body;
  if (!r_id || !date || !comment || !u_id || !c_id || !rating) {
    return res.status(400).send('All fields except r_img_url are required');
  }

  try {
    const result = await insertReview(r_id, date, comment, r_img_url || null, u_id, c_id, rating);
    res.json({ message: 'Review added successfully', result });
  } catch (error) {
    res.status(500).send('Error inserting review');
  }
});

module.exports = router;
