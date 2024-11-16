const express = require('express');
const userRoutes = require('./routes/users');
const ordersRoutes = require('./routes/orders');
const detailRoutes = require('./routes/detail');
const reviewRoutes = require('./routes/review');
const consumerRoutes = require('./routes/consumer');
const addressRoutes = require('./routes/address');
const cartRoutes = require('./routes/cart');
const paymentsRoutes = require('./routes/payments');

const app = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(express.json());

// Root Route
app.get('/', (req, res) => {
  res.send('Welcome to the API!');
});

// Routes
app.use('/api/users', userRoutes);

// Add orders routes
app.use('/api/orders', ordersRoutes);

// Add detail routes
app.use('/api/detail', detailRoutes);

// Add review routes
app.use('/api/reviews', reviewRoutes);

// Add consumer routes
app.use('/api/consumer', consumerRoutes);

// Add address routes
app.use('/api/addresses', addressRoutes);

// Add cart routes
app.use('/api/cart', cartRoutes);

// Add payments routes
app.use('/api/payments', paymentsRoutes);


app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});
