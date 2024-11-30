<?php
// Start session and include database connection
session_start();
include("db_connection.php");

// Validate user session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$f_id = $_SESSION['user_id']; // Fetch user ID from session

// Helper function to fetch data using prepared statements
function fetchSingleRow($conn, $query, $params, $types)
{
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}

// Fetch the active cart ID
$cartQuery = "SELECT cart_id FROM cart WHERE u_id = ? AND active = TRUE";
$cartRow = fetchSingleRow($conn, $cartQuery, [$f_id], "i");

if (!$cartRow) {
    $cart_id = null;
} else {
    $cart_id = $cartRow["cart_id"];
}

// Fetch orders for the active cart if the cart exists
$orderData = [];
if ($cart_id) {
    $orderQuery = "SELECT * FROM orders WHERE cart_id = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $ordersResult = $stmt->get_result();
    $stmt->close();

    // Fetch crop and farmer details for each order
    while ($row = $ordersResult->fetch_assoc()) {
        $crop_id = $row["c_id"];
        $amount = $row["amount"];
        $price = $row["price"];

        $cropQuery = "SELECT * FROM crop WHERE c_id = ?";
        $cropRow = fetchSingleRow($conn, $cropQuery, [$crop_id], "i");

        $farmerQuery = "SELECT name FROM farmer WHERE f_id = ?";
        $farmerRow = fetchSingleRow($conn, $farmerQuery, [$cropRow['f_id']], "i");

        $orderData[] = [
            'crop_name' => htmlspecialchars($cropRow["c_name"]),
            'crop_image' => htmlspecialchars($cropRow["img_url"]),
            'farmer_name' => htmlspecialchars($farmerRow["name"]),
            'amount' => $amount,
            'unit' => htmlspecialchars($cropRow["unit"]),
            'price' => $price
        ];
    }
}

// Handle order placement
$orderPlaced = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if ($cart_id) {
        $orderUpdate = "UPDATE orders SET status = 'Completed' WHERE cart_id = ?";
        $cartUpdate = "UPDATE cart SET active = FALSE WHERE cart_id = ?";

        $stmt = $conn->prepare($orderUpdate);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare($cartUpdate);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();

        $orderPlaced = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Splash Screen Styling */
        .splash-screen {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .splash-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 20px;
            font-weight: bold;
            color: #388e3c;
        }

        .empty-cart-message {
            font-size: 18px;
            text-align: center;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include("header.php"); ?>

    <main>
        <div class="container">
            <!-- Orders Section -->
            <div class="orders">
                <div class="title">Your Orders</div>

                <?php if (empty($orderData)): ?>
                    <p class="empty-cart-message">Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($orderData as $order): ?>
                        <div class="cart-item">
                            <img src="<?= $order['crop_image'] ?>" alt="<?= $order['crop_name'] ?>">
                            <div class="cart-item-content">
                                <h3><?= $order['crop_name'] ?></h3>
                                <p><?= $order['farmer_name'] ?></p>
                                <p>Qty: <?= $order['amount'],$order['unit']?></p>
                            </div>
                            <div class="cart-item-price">₹<?= $order['price'] ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Price Details Section -->
            <?php if (!empty($orderData)): ?>
                <div class="price-details">
                    <div class="title">Price Details</div>
                    <?php
                    $totalPrice = 0;
                    foreach ($orderData as $order) {
                        $totalPrice += $order['price'];
                        echo '
                            <div class="price-row">
                                <span>' . $order['crop_name'] . '</span>
                                <span>₹' . $order['price'] . '</span>
                            </div>';
                    }
                    ?>
                    <div class="total">
                        <span>Total</span>
                        <span>₹<?= $totalPrice ?></span>
                    </div>
                    <form method="POST">
                        <button type="submit" name="place_order" class="place-order-button">Place Your Order</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Splash Screen -->
    <?php if ($orderPlaced): ?>
        <div id="splash-screen" class="splash-screen">
            <div class="splash-content">Order Placed Successfully!</div>
        </div>

        <script>
            // Show splash screen for 3 seconds, then reload the page
            document.getElementById('splash-screen').style.display = "flex";
            setTimeout(function () {
                window.location.href = "<?= $_SERVER['PHP_SELF'] ?>";
            }, 1000); // 3 seconds
        </script>
    <?php endif; ?>
</body>

</html>
