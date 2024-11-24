<?php
// Start the session
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a review.";
    exit;
}

// Get data from the form submission
if (isset($_POST['order_id']) && isset($_POST['c_id']) && isset($_POST['comment']) && isset($_POST['rating'])) {
    $order_id = $_POST['order_id'];
    $c_id = $_POST['c_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    $review_image_url = $_POST['review_image_url'];
    $user_id = $_SESSION['user_id']; // User ID from session
} else {
    $_SESSION['review_message'] = "Missing review data!";
    header("Location: order_details.php?order_id=$order_id");
    exit;
}

// Check if the order_id exists in the orders table
$sql_check_order = "SELECT * FROM orders WHERE order_id = ?";
$stmt_check_order = $conn->prepare($sql_check_order);
$stmt_check_order->bind_param("i", $order_id);
$stmt_check_order->execute();
$result_check_order = $stmt_check_order->get_result();

if ($result_check_order->num_rows == 0) {
    // Order does not exist
    $_SESSION['review_message'] = "Invalid order ID!";
    header("Location: order_details.php?order_id=$order_id");
    exit;
}

$stmt_check_order->close();

// Check if review already exists for the user and product (c_id)
$sql_check_review = "SELECT * FROM review WHERE u_id = ? AND c_id = ?";
$stmt_check_review = $conn->prepare($sql_check_review);
$stmt_check_review->bind_param("ii", $user_id, $c_id);
$stmt_check_review->execute();
$result_check_review = $stmt_check_review->get_result();

if ($result_check_review->num_rows > 0) {
    // Update existing review
    $sql_update = "UPDATE review SET comment = ?, rating = ?, r_img_url = ?, date = NOW() WHERE u_id = ? AND c_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssiis", $comment, $rating, $review_image_url, $user_id, $c_id);

    if ($stmt_update->execute()) {
        $_SESSION['review_message'] = "Your review has been updated successfully!";
    } else {
        $_SESSION['review_message'] = "Error updating the review. Please try again.";
    }
    $stmt_update->close();
} else {
    // Insert new review into review table, using the provided order_id, user_id, and product (c_id)
    $sql_insert = "INSERT INTO review (u_id, c_id, comment, rating, r_img_url, date) 
                   VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iisss", $user_id, $c_id, $comment, $rating, $review_image_url);

    if ($stmt_insert->execute()) {
        $_SESSION['review_message'] = "Your review has been submitted successfully!";
    } else {
        $_SESSION['review_message'] = "Error submitting the review. Please try again.";
    }
    $stmt_insert->close();
}

$stmt_check_review->close();
$conn->close();

// Redirect back to order details page with order_id
header("Location: order_details.php?order_id=$order_id");
exit;
?>
