<?php
// Start the session
session_start();

include("db_connection.php");

// Fetch farmer ID from session
$f_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the sign-in page
    header("Location: login.php");
    exit();
}

// Fetch farmer's details
$sql_farmer = "SELECT name FROM farmer WHERE f_id = $f_id";
$result_farmer = $conn->query($sql_farmer);
$farmer = $result_farmer->fetch_assoc();

// Fetch farmer's crops
$sql_crops = "SELECT * FROM crop WHERE f_id = $f_id";
$result_crops = $conn->query($sql_crops);

// Fetch farmer's stats
$sql_stats = "SELECT 
                COUNT(*) AS total_delivered,
                AVG(review.rating) AS avg_rating
              FROM crop
              LEFT JOIN review ON crop.c_id = review.c_id
              WHERE crop.f_id = $f_id";
$result_stats = $conn->query($sql_stats);
$stats = $result_stats->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/farmer_dashboard.css"> <!-- Link to external CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
     <?php include("header.php"); ?>
    <div class="farmer-name">
        <h1>Welcome, <?= htmlspecialchars($farmer['name']) ?>!</h1>
        <p>Your personalized dashboard is here.</p>
    </div>

    <!-- Stats Section -->
    <section class="stats">
        <div>
            <h3><?= $stats['total_delivered'] ?? 0 ?></h3>
            <p>Products Delivered</p>
        </div>
        <div>
            <h3><?= number_format($stats['avg_rating'], 1) ?? 'N/A' ?></h3>
            <p>Average Rating</p>
        </div>
    </section>

    <!-- Crops Section -->
    <section class="crops">
        <h2>Your Crops</h2>
        <div class="crop-cards">
            <?php
            if ($result_crops->num_rows > 0) {
                while ($row = $result_crops->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($row['img_url']) . '" alt="' . htmlspecialchars($row['c_name']) . '">';
                    echo '<div class="card-content">';
                    echo '<h3>' . strtoupper(htmlspecialchars($row['c_name'])) . '</h3>';
                    echo '<p>Quantity: ' . htmlspecialchars($row['c_qty']) . ' ' . htmlspecialchars($row['unit']) . '</p>';
                    echo '<p>Shelf Life: ' . ($row['shelf_life'] ?? 'N/A') . ' days</p>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<span class="price">₹' . htmlspecialchars(number_format($row['ppu'], 2)) . '</span>';
                    echo '<span>Per ' . htmlspecialchars($row['unit']) . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No crops found!</p>';
            }
            ?>
        </div>
    </section>
</body>
</html>

<?php
$conn->close();
?>
