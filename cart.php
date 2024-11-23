
<?php
// Start the session
session_start();

include("db_connection.php");

// Fetch farmer ID from session, this id is f_id for farmers and c_id for consumers
$f_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the sign-in page
    header("Location: login.php");
    exit();
}

// Other queries
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="cart.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
  <main>
    <div class="container">
      <!-- Orders Section -->
      <div class="orders">
        <div class="title">Your Orders</div>

        <?php  


        
        
        
        
        ?>




        <div class="cart-item">
          <img src="https://via.placeholder.com/60" alt="Watermelon">
          <div class="cart-item-content">
            <h3>Watermelon</h3>
            <p>Rani Mausi</p>
            <p>Qty: 2 pcs</p>
            
          </div>
          <div class="cart-item-price">₹199</div>
        </div>
         <div class="cart-item">
          <img src="https://via.placeholder.com/60" alt="Watermelon">
          <div class="cart-item-content">
            <h3>Watermelon</h3>
            <p>Rani Mausi</p>
            <p>Qty: 2 pcs</p>
            
          </div>
          <div class="cart-item-price">₹199</div>
        </div>
        <div class="cart-item">
          <img src="https://via.placeholder.com/60" alt="Tomatoes">
          <div class="cart-item-content">
            <h3>Tomatoes</h3>
            <p>Rani Mausi</p>
            <p>Qty: 2 kg</p>
          </div>
          <div class="cart-item-price">₹99</div>
        </div>
        <div class="cart-item">
          <img src="https://via.placeholder.com/60" alt="Beans">
          <div class="cart-item-content">
            <h3>Beans</h3>
            <p>Rani Mausi</p>
            <p>Qty: 0.5 kg</p>
          </div>
          <div class="cart-item-price">₹20</div>
        </div>
      </div>

      <!-- Price Details Section -->
      <div class="price-details">
        <div class="title">Price Details</div>
        <div class="price-row">
          <span>Watermelon</span>
          <span>₹199</span>
        </div>
        <div class="price-row">
          <span>Tomatoes</span>
          <span>₹99</span>
        </div>
        <div class="price-row">
          <span>Beans</span>
          <span>₹20</span>
        </div>
        <div class="total">
          <span>Total</span>
          <span>₹319</span>
        </div>
        <button class="place-order-button">Place Your Order</button>
      </div>
    </div>
  </main>
</body>
</html>
