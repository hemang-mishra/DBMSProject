<?php
session_start();
require_once 'db_connection.php'; // Ensure the database connection file is included

// Fetch farmer details (assuming farmer ID is stored in session)
$f_id = $_SESSION['user_id']; // Replace 1 with an actual farmer ID for testing
$query = "SELECT name FROM farmer WHERE f_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $f_id);
$stmt->execute();
$farmer = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch orders and corresponding consumer details
$sql = "
    SELECT c.uname AS consumer_name, o.date, o.status, o.price, o.amount, crop.c_name AS crop_name, crop.img_url AS crop_image
    FROM orders o
    JOIN consumer c ON o.u_id = c.u_id
    JOIN crop ON o.c_id = crop.c_id
    WHERE crop.f_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $f_id);
$stmt->execute();
$orders = $stmt->get_result();

$stats_sql = "
    SELECT 
        COUNT(DISTINCT o.u_id) AS unique_customers,
        SUM(o.amount) AS total_quantity_sold,
        SUM(o.price) AS total_order_amount_sold
    FROM orders o
    JOIN crop c ON o.c_id = c.c_id
    WHERE c.f_id = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $f_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result()->fetch_assoc();
$stats_stmt->close();

// Extract the stats values
$unique_customers = $stats_result['unique_customers'];
$total_quantity_sold = $stats_result['total_quantity_sold'];
$total_order_amount_sold = $stats_result['total_order_amount_sold'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your styles.css -->
    <link rel="stylesheet" href="css/f_profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include("header.php"); ?>

    <div class="container">
        <!-- Stats Container -->
        <div class="stats-container">
            <div class="stats-item">
                <h3>Unique Customers</h3>
                <p><?= htmlspecialchars($unique_customers); ?></p>
            </div>
            <div class="stats-item">
                <h3>Total Quantity Sold</h3>
                <p><?= htmlspecialchars($total_quantity_sold); ?> units</p>
            </div>
            <div class="stats-item">
                <h3>Total Order Amount Sold</h3>
                <p>₹<?= htmlspecialchars($total_order_amount_sold); ?></p>
            </div>
        </div>

        <h2>Order Details</h2>
        
        <div class="order-container">
            <?php
            // Display orders in stacked blocks
            if ($orders->num_rows > 0) {
                while ($row = $orders->fetch_assoc()) {
                    $consumer_name = htmlspecialchars($row['consumer_name']);
                    $crop_name = htmlspecialchars($row['crop_name']);
                    $quantity = htmlspecialchars($row['amount']);
                    $price = htmlspecialchars($row['price']);
                    $date = htmlspecialchars($row['date']);
                    $status = htmlspecialchars($row['status']); // Add status
                    $image_url = htmlspecialchars($row['crop_image'] ?? "https://via.placeholder.com/60"); // Fallback if no image

                    // Determine status display
                    $status_text = ($status === "Completed") ? "✔ Completed" : "⌛ Pending";

                    echo "
                        <div class='order-block'>
                            <div class='order-image'>
                                <img src='$image_url' alt='$crop_name'>
                            </div>
                            <div class='order-details'>
                                <h3>$crop_name</h3>
                                <p>Consumer: $consumer_name</p>
                                <p>Quantity: $quantity</p>
                                <p>Price: ₹$price</p>
                                <p>Date: $date</p>
                                <p class='order-status'>$status_text</p> <!-- Status -->
                            </div>
                        </div>
                    ";
                }
            } else {
                echo "<p>No orders found for this farmer.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
    <form method="POST" action="logout.php">
        <button class="logout-button" type="submit">Logout</button>
    </form>
</body>
</html>
