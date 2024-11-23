<?php

include 'header.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view your order history.";
    exit;
}

// Include the database connection file
require_once 'db_connection.php'; // Ensure this file correctly sets up $conn

// Logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user with the updated query (without payment method)
$sql = "
    SELECT 
        orders.order_id, 
        orders.date, 
        orders.status, 
        orders.price, 
        orders.amount, 
        crop.c_name AS crop_name, 
        shipping_address.city AS shipping_city,
        shipping_address.state AS shipping_state
    FROM orders
    JOIN crop ON orders.c_id = crop.c_id
    JOIN shipping_address ON orders.addr_id = shipping_address.addr_id
    WHERE orders.u_id = ?
    ORDER BY orders.date DESC, orders.status ASC
";

$stmt = $conn->prepare($sql); // Use $conn from db_connection.php
$stmt->bind_param("i", $user_id); // Bind the user_id as parameter
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="css/c_order.css"> <!-- Link to external CSS if needed -->
</head>
<body>
    <header>
        <h1>Your Order History</h1>
    </header>

    <?php
    // Display orders in a table
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Quantity</th>
                <th>Crop Name</th>
                <th>Shipping Address</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            // Format the shipping address (combine city, state)
            $shipping_address = $row['shipping_city'] . ", " . $row['shipping_state'];

            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['status']}</td>
                    <td>&#8377;{$row['price']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['crop_name']}</td>
                    <td>{$shipping_address}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>You have not placed any orders yet.</p>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
