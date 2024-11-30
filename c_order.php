<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view your order history.";
    exit;
}

// Include the database connection file
require_once 'db_connection.php';

// Logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$sql = "
    SELECT 
        orders.order_id, 
        orders.date, 
        orders.price, 
        orders.amount, 
        orders.status, -- Add status field
        crop.c_name AS crop_name, 
        crop.img_url AS crop_image
    FROM orders
    JOIN crop ON orders.c_id = crop.c_id
    WHERE orders.u_id = ?
    ORDER BY orders.date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="css/c_order.css"> <!-- External CSS -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <?php include("header.php"); ?>
    <header>
        <h1>Your Order History</h1>
    </header>

    <div class="order-container">
        <?php
        // Display orders in stacked blocks
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $order_id = $row['order_id'];
                $crop_name = htmlspecialchars($row['crop_name']);
                $quantity = htmlspecialchars($row['amount']);
                $price = htmlspecialchars($row['price']);
                $date = htmlspecialchars($row['date']);
                $status = htmlspecialchars($row['status']); // Add status
                $image_url = htmlspecialchars($row['crop_image'] ?? "https://via.placeholder.com/60"); // Fallback if no image

                // Determine status display
                $status_text = ($status === "Completed") ? "✔ Completed" : "⌛ Pending";

                echo "
                    <div class='order-block' onclick=\"window.location.href='order_details.php?order_id=$order_id'\">
                        <div class='order-image'>
                            <img src='$image_url' alt='$crop_name'>
                        </div>
                        <div class='order-details'>
                            <h3>$crop_name</h3>
                            <p>Quantity: $quantity</p>
                            <p>Price: ₹$price</p>
                            <p>Date: $date</p>
                            <p class='order-status'>$status_text</p> <!-- Status -->
                        </div>
                    </div>
                ";
            }
        } else {
            echo "<p>You have not placed any orders yet.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
