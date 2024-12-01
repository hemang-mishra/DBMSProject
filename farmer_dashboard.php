<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the sign-in page
    header("Location: login.php");
    exit();
}

include("db_connection.php");

// Fetch farmer's details
$f_id = $_SESSION['user_id'];
$sql_farmer = "SELECT name FROM farmer WHERE f_id = $f_id";
$result_farmer = $conn->query($sql_farmer);
$farmer = $result_farmer->fetch_assoc();

// Get the search query if it exists
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Fetch farmer's crops with average rating and number of orders
if (!empty($search_query)) {
    $sql_crops = "SELECT crop.c_id, crop.c_name,crop.is_available, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND (crop.c_name LIKE ? OR farmer.name LIKE ?)
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt = $conn->prepare($sql_crops);
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param('iss', $f_id, $search_term, $search_term);
    $stmt->execute();
    $result_crops = $stmt->get_result();
} else {
    $sql_crops = "SELECT crop.c_id, crop.c_name,crop.is_available, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ?
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt = $conn->prepare($sql_crops);
    $stmt->bind_param('i', $f_id);
    $stmt->execute();
    $result_crops = $stmt->get_result();
}

// Fetch farmer's stats
$sql_stats = "SELECT 
                COUNT(orders.order_id) AS total_delivered,
                AVG(review.rating) AS avg_rating
              FROM crop
              LEFT JOIN review ON crop.c_id = review.c_id
              LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
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
    <link rel="stylesheet" href="css/farmer_dashboard.css"> <!-- Link to external CSS in css folder-->
    <link rel="stylesheet" href="css/consumer_dashboard.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include("header_f.php"); ?>

    <!-- Farmer Name Section -->
    <div class="farmer-name">
        <h1>Welcome, <?php echo htmlspecialchars($farmer['name']); ?>!</h1>
    </div>

    <!-- Stats Section -->
    <div class="stats">
        <div>
            <h3>Total Delivered</h3>
            <p><?php echo htmlspecialchars($stats['total_delivered']); ?></p>
        </div>
        <div>
            <h3>Average Rating</h3>
            <p><?php echo number_format($stats['avg_rating'], 1); ?>★</p>
        </div>
    </div>


    <!-- Crops Section -->
    <div class="crops">
        <h2>Your Crops</h2>
        <div class="crop-cards">
            <?php while ($crop = $result_crops->fetch_assoc()): ?>
                <form method="POST" action="crop_details.php" class="card-form">
                <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($crop['c_id']); ?>">
                
                <div class="card" onclick="submitForm(this);">
                    <div class="card-header">
                        <div class="product-icon">
                        <img src="<?php echo htmlspecialchars($crop['img_url']); ?>" alt="<?php echo htmlspecialchars($crop['c_name']); ?> Icon" class="<?php echo $crop['is_available'] ? '' : 'grayscale'; ?>" />
                        </div>
                        <div class="shelf-life">
                            <?php echo htmlspecialchars($crop['shelf_life']); ?> days
                        </div> 
                    </div>
                    <div class="card-content">
                        <h3 class="product-title">
                            <?php echo htmlspecialchars($crop['c_name']); ?>
                        </h3> 
                        <?php if ($crop['is_available']): ?>
                            <p class="product-price">₹<?php echo htmlspecialchars($crop['ppu']); ?>/<?php echo htmlspecialchars($crop['unit']); ?></p>
                        <?php else: ?>
                            <p class="product-price">Not for sale</p>
                        <?php endif; ?>
                        <p class="rating">
                            <?php if ($crop['review_count'] > 0): ?>
                                <?php echo number_format($crop['avg_rating'], 1); ?>★ (<?php echo htmlspecialchars($crop['review_count']); ?> reviews)
                            <?php else: ?>
                                Not reviewed yet
                            <?php endif; ?>
                        </p>
                        <p class="order-count">
                            <?php echo htmlspecialchars($crop['order_count']); ?> orders
                        </p>
                    </div>
                </div>
                </form>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function submitForm(card) {
            console.log("Card clicked:", card); // Debugging statement
            const form = card.closest('form');
            console.log("Form found:", form); // Debugging statement
            if (form) {
                form.submit();
            } else {
                console.error("Form not found for card:", card); // Debugging statement
            }
        }

         // JavaScript to handle the delete button hover effect
         document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseover', () => {
                card.querySelector('.delete-btn').style.display = 'block';
            });
            card.addEventListener('mouseout', () => {
                card.querySelector('.delete-btn').style.display = 'none';
            });
        });

        // JavaScript to confirm deletion
        function confirmDelete() {
            return confirm('Are you sure you want to delete this crop?');
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>