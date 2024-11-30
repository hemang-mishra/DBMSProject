<?php
session_start();
include("../db_connection.php");

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

// Get the crop ID and quantity from POST request
if (isset($_POST['c_id']) && isset($_POST['quantity'])) {
    $c_id = intval($_POST['c_id']);
    $quantity = intval($_POST['quantity']);
} else {
    die("Invalid request: Crop ID or quantity not set.");
}

// Get the current date
$date = date('Y-m-d');

// Check if the crop exists and get its price
$sql_crop = "SELECT ppu FROM crop WHERE c_id = ?";
$stmt_crop = $conn->prepare($sql_crop);
$stmt_crop->bind_param("i", $c_id);
$stmt_crop->execute();
$result_crop = $stmt_crop->get_result();

if ($result_crop->num_rows > 0) {
    $crop = $result_crop->fetch_assoc();
    $price_per_unit = $crop['ppu'];
    $total_price = $price_per_unit * $quantity;
} else {
    die("Invalid crop ID: " . htmlspecialchars($c_id));
}

// Check for an active cart for the user
$sql_get_cart = "SELECT cart_id FROM cart WHERE u_id = ? AND active = TRUE LIMIT 1";
$stmt_cart = $conn->prepare($sql_get_cart);
$stmt_cart->bind_param("i", $u_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

if ($result_cart->num_rows > 0) {
    // Active cart exists
    $cart = $result_cart->fetch_assoc();
    $cart_id = $cart['cart_id'];
} else {
    // No active cart, create one
    // Generate a new unique cart_id
    $sql_max_cart_id = "SELECT MAX(cart_id) AS max_cart_id FROM cart";
    $result_max_cart_id = $conn->query($sql_max_cart_id);

    if (!$result_max_cart_id) {
        die("Error fetching max cart_id: " . $conn->error);
    }

    $row_max_cart_id = $result_max_cart_id->fetch_assoc();
    $new_cart_id = $row_max_cart_id['max_cart_id'] + 1;

    // Create a new cart
    $total_price_1 = 0;
    $active = TRUE;
    $created_date = date('Y-m-d');

    $sql_create_cart = "INSERT INTO cart (cart_id, total_price_1, active, created_date, u_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_create_cart = $conn->prepare($sql_create_cart);

    if (!$stmt_create_cart) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt_create_cart->bind_param("idisi", $new_cart_id, $total_price, $active, $created_date, $u_id);

    if (!$stmt_create_cart->execute()) {
        die("Execute failed: (" . $stmt_create_cart->errno . ") " . $stmt_create_cart->error);
    }

    $cart_id = $new_cart_id;
}

// Generate a new unique order_id
$sql_max_order_id = "SELECT MAX(order_id) AS max_order_id FROM orders";
$result_max_order_id = $conn->query($sql_max_order_id);

if (!$result_max_order_id) {
    die("Error fetching max order_id: " . $conn->error);
}

$row_max_order_id = $result_max_order_id->fetch_assoc();
$new_order_id = $row_max_order_id['max_order_id'] + 1;

// Insert the new order
$sql_create_order = "INSERT INTO orders (order_id, date, status, price, amount, addr_id, id, cart_id, u_id, c_id) VALUES (?, ?, 'Pending', ?, ?, NULL, NULL, ?, ?, ?)";
$stmt_create_order = $conn->prepare($sql_create_order);

if (!$stmt_create_order) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_create_order->bind_param("isdiiii", $new_order_id, $date, $total_price, $quantity, $cart_id, $u_id, $c_id);

if (!$stmt_create_order->execute()) {
    die("Execute failed: (" . $stmt_create_order->errno . ") " . $stmt_create_order->error);
}

// Redirect after successful order creation
header("Location: ../cart.php");
exit();

$conn->close();
?>