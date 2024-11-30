<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view the order details.";
    exit;
}

// Include the database connection file
require_once 'db_connection.php';

// Get the order_id from the query parameter
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "Order ID is required.";
    exit;
}

// Prepare the SQL query to fetch order details
$sql = "
    SELECT 
        orders.order_id, 
        orders.date, 
        orders.status, 
        orders.price, 
        orders.amount, 
        shipping_address.city, 
        shipping_address.addr_line_1, 
        shipping_address.addr_line_2, 
        crop.c_id,                 -- Ensure `c_id` is fetched here
        crop.c_name AS crop_name, 
        crop.img_url AS crop_image, 
        consumer.uname AS consumer_name
    FROM orders
    JOIN crop ON orders.c_id = crop.c_id
    JOIN shipping_address ON orders.addr_id = shipping_address.addr_id
    JOIN consumer ON orders.u_id = consumer.u_id
    WHERE orders.order_id = ?
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $order_id = htmlspecialchars($order['order_id']);
    $crop_name = htmlspecialchars($order['crop_name']);
    $date = htmlspecialchars($order['date']);
    $status = htmlspecialchars($order['status']);
    $price = htmlspecialchars($order['price']);
    $amount = htmlspecialchars($order['amount']);
    $city = htmlspecialchars($order['city']);
    $addr_line_1 = htmlspecialchars($order['addr_line_1']);
    $addr_line_2 = htmlspecialchars($order['addr_line_2']);
    $consumer_name = htmlspecialchars($order['consumer_name']);
    $image_url = htmlspecialchars($order['crop_image'] ?? "https://via.placeholder.com/60"); // Fallback if no image
} else {
    echo "Order not found.";
    exit;
}

$stmt->close();
$conn->close();

// Get the review message from session (if set)
$review_message = isset($_SESSION['review_message']) ? $_SESSION['review_message'] : '';
unset($_SESSION['review_message']); // Clear the message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/order_details.css"> <!-- External CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <?php include("header.php"); ?>
    <header>
        <h1>Order Details</h1>
    </header>

    <div class="order-details-container">
        <!-- Display the review message -->
        <?php if ($review_message): ?>
            <div class="review-message">
                <p><?php echo $review_message; ?></p>
            </div>
        <?php endif; ?>

        <!-- Order Details Section -->
        <div class="order-detail">
            <h2>Order ID: <?php echo $order_id; ?></h2>
            <div class="order-info">
                <div class="order-image">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $crop_name; ?>" />
                </div>
                <div class="order-details">
                    <p><strong>Crop Name:</strong> <?php echo $crop_name; ?></p>
                    <p><strong>Date:</strong> <?php echo $date; ?></p>
                    <p><strong>Status:</strong> <?php echo $status; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo $price; ?></p>
                    <p><strong>Amount:</strong> <?php echo $amount; ?></p>
                    <p><strong>Address:</strong> <?php echo $addr_line_1 . ", " . $addr_line_2 . ", " . $city; ?></p>
                    <p><strong>Consumer Name:</strong> <?php echo $consumer_name; ?></p>
                </div>
            </div>
        </div>

        <!-- Review Section -->
        <div class="review-section">
            <h2>Write a Review</h2>
            <form action="submit_review.php" method="POST">
                <label for="comment">Review:</label>
                <textarea id="comment" name="comment" placeholder="Write your review..."></textarea>

                <label for="rating">Rating:</label>
                <input type="hidden" id="rating" name="rating" value="0">
                <div class="star-rating">
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                </div>

                <label for="review_image_url">Image URL (optional):</label>
                <input type="text" id="review_image_url" name="review_image_url" placeholder="http://example.com/image.jpg">

                <input type="hidden" name="c_id" value="<?php echo isset($order['c_id']) ? htmlspecialchars($order['c_id']) : ''; ?>">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"> <!-- Pass the order_id here -->

                <button type="submit">Submit Review</button>
            </form>
        </div>

    </div>
</body>
</html>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating');
    let selectedRating = 0;

    stars.forEach((star) => {
        star.addEventListener('mouseover', () => {
            resetStars();
            const value = parseInt(star.dataset.value, 10);
            highlightStars(value);
        });

        star.addEventListener('mouseout', () => {
            resetStars();
            if (selectedRating > 0) highlightStars(selectedRating);
        });

        star.addEventListener('click', () => {
            selectedRating = parseInt(star.dataset.value, 10);
            ratingInput.value = selectedRating; // Update the hidden input value
        });
    });

    function highlightStars(value) {
        stars.forEach((star) => {
            if (parseInt(star.dataset.value, 10) <= value) {
                star.classList.add('selected');
            }
        });
    }

    function resetStars() {
        stars.forEach((star) => {
            star.classList.remove('selected');
        });
    }
});

</script>
