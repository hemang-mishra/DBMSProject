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
                //   echo "<div style='color: orange; font-weight: bold;'>Warning: Value is less than ".$f_id."!</div>";
                $cart_id = "SELECT cart_id from cart where u_id = '$f_id' and active = TRUE";
                $cart_id_result = $conn->query($cart_id);
                $row = $cart_id_result->fetch_assoc();
                if ($cart_id_result->num_rows <= 0) {

                    header("Location:login.php");
                    exit();
                }
                $cart = $row["cart_id"];
                // echo "<div style='color: orange; font-weight: bold;'>Warning: Value is less than ".$cart."!</div>";
                $ordered = "SELECT * FROM orders WHERE cart_id = '$cart'";
                $ordered_result = $conn->query($ordered);
                $arr_crop = [];
                $arr_price = [];
                while ($row = $ordered_result->fetch_assoc()) {

                    $crop_id = $row["c_id"];
                    $ammount = $row["amount"];
                    $price = $row["price"];
                    $arr_price[] = $price;
                    // echo "<div style='color: orange; font-weight: bold;'>Warning: Value is less than ".$crop_id."!</div>";
                    $crop_q1 = "SELECT * FROM crop where c_id = '$crop_id'";
                    $crop_q1_result = $conn->query($crop_q1);
                    $crop_row = $crop_q1_result->fetch_assoc();
                    $crop_name = $crop_row["c_name"];
                    $arr_crop[] = $crop_name;
                    $crop_image = $crop_row["img_url"];
                    $farmer_id = $crop_row["f_id"];

                    $farmer_q1 = "SELECT * FROM farmer where f_id = '$farmer_id'";
                    $farmer_q1_result = $conn->query($farmer_q1);
                    $farmer_row = $farmer_q1_result->fetch_assoc();
                    $farmer_name = $farmer_row["name"];

                    echo '<div class="cart-item">
                <img src="' . $crop_image . '" alt="Watermelon">
                <div class="cart-item-content">
                <h3>' . $crop_name . '</h3>
                <p>' . $farmer_name . '</p>
                <p>Qty: ' . $ammount . ' gram</p>
            </div>
            <div class="cart-item-price">₹' . $price . '</div>
            </div>';
                }
                ?>
            </div>

            <!-- Price Details Section -->
            <div class="price-details">
                <div class="title">Price Details</div>

                <?php

                for ($i = 0; $i < count($arr_crop); $i++) {
                    echo '
            <div class="price-row">
            <span>' . $arr_crop[$i] . '</span>
            <span>₹' . $arr_price[$i] . '</span>
            </div>';
                }
                ?>
                <div class="total">
                    <span>Total</span>
                    <span>₹<?php
                            $sum = array_sum($arr_price);
                            echo $sum;
                            ?></span>
                </div>
                <form method="POST">
                    <button type="submit" name="place_order" class="place-order-button">Place Your Order</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>


<?php
// Variable to simulate the order being placed (You can replace this logic with your actual order processing)
$orderPlaced = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    // Simulate placing the order
    $orderPlaced = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placement</title>
    <link rel="stylesheet" href="splash.css">
</head>

<body>
    <!-- Splash Screen Overlay -->
    <?php if ($orderPlaced): ?>
    <div id="splash-screen" class="splash-screen show">
        <div>Order Placed Successfully!</div>
    </div>

    <script>
    // Automatically hide the splash screen after 3 seconds
    setTimeout(function() {
        document.getElementById('splash-screen').classList.add('hide');
    }, 2000); // 3 seconds
    </script>
    <?php endif;
    ?>

</body>

</html>




<?php

if ($orderPlaced) {

    $complete_order = "UPDATE orders SET status = 'Completed' WHERE cart_id = ?";

    // Step 3: Prepare and bind the statement to prevent SQL injection
    $stmt = $conn->prepare($complete_order);
    $stmt->bind_param("i", $cart); // 'i' means the variable $cart is an integer

    // Step 4: Execute the query
    if ($stmt->execute()) {
        echo "Order status updated to 'Completed'.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Step 5: Close the statement and connection
    $stmt->close();

    $complete_cart = "UPDATE cart SET active = FALSE WHERE cart_id = ?";

    // Step 3: Prepare and bind the statement to prevent SQL injection
    $stmt = $conn->prepare($complete_cart);
    $stmt->bind_param("i", $cart); // 'i' means the variable $cart is an integer

    // Step 4: Execute the query
    if ($stmt->execute()) {
        echo "Cart status updated to 'Inactive'.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Step 5: Close the statement and connection
    $stmt->close();

    header("Location:login.php");
    exit();
}



?>