<?php
session_start();
include("db_connection.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Get the consumer ID from the session
$u_id = $_SESSION['user_id'];

// Get the crop ID, rating, comment, and review image URL from POST request
if (isset($_POST['c_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $c_id = intval($_POST['c_id']);
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];
    $r_img_url = isset($_POST['r_img_url']) ? $_POST['r_img_url'] : null;
} else {
    die("Invalid request: Crop ID, rating, or comment not set.");
}

// Get the current date
$date = date('Y-m-d');

// Check if the user has already reviewed this crop
$sql_check_review = "SELECT r_id FROM review WHERE u_id = ? AND c_id = ?";
$stmt_check_review = $conn->prepare($sql_check_review);

if (!$stmt_check_review) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_check_review->bind_param("ii", $u_id, $c_id);
$stmt_check_review->execute();
$result_check_review = $stmt_check_review->get_result();

if ($result_check_review->num_rows > 0) {
    // Review exists, update the existing review
    $review = $result_check_review->fetch_assoc();
    $r_id = $review['r_id'];

    $sql_update_review = "UPDATE review SET date = ?, comment = ?, r_img_url = ?, rating = ? WHERE r_id = ?";
    $stmt_update_review = $conn->prepare($sql_update_review);

    if (!$stmt_update_review) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt_update_review->bind_param("sssii", $date, $comment, $r_img_url, $rating, $r_id);

    if (!$stmt_update_review->execute()) {
        die("Execute failed: (" . $stmt_update_review->errno . ") " . $stmt_update_review->error);
    }
} else {
    // Review does not exist, insert a new review
    // Generate a new unique r_id
    $sql_max_r_id = "SELECT MAX(r_id) AS max_r_id FROM review";
    $result_max_r_id = $conn->query($sql_max_r_id);

    if (!$result_max_r_id) {
        die("Error fetching max r_id: " . $conn->error);
    }

    $row_max_r_id = $result_max_r_id->fetch_assoc();
    $new_r_id = $row_max_r_id['max_r_id'] + 1;

    $sql_insert_review = "INSERT INTO review (r_id, date, comment, r_img_url, rating, u_id, c_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_review = $conn->prepare($sql_insert_review);

    if (!$stmt_insert_review) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt_insert_review->bind_param("isssiii", $new_r_id, $date, $comment, $r_img_url, $rating, $u_id, $c_id);

    if (!$stmt_insert_review->execute()) {
        die("Execute failed: (" . $stmt_insert_review->errno . ") " . $stmt_insert_review->error);
    }
}

// Redirect after successful review submission
header("Location: c_order.php");
exit();

$conn->close();
?>