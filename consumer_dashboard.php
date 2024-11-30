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

// Get the search query if it exists
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Fetch crops from the database with average rating
if (!empty($search_query)) {
    $sql_crops = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  WHERE crop.c_name LIKE ? OR farmer.name LIKE ?
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt = $conn->prepare($sql_crops);
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param('ss', $search_term, $search_term);
    $stmt->execute();
    $result_crops = $stmt->get_result();
} else {
    $sql_crops = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $result_crops = $conn->query($sql_crops);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/consumer_dashboard.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include("header.php"); ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome to GreenLeaf</h1>
        <p>Discover and purchase fresh crops directly from farmers.</p>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
        <?php while ($crop = $result_crops->fetch_assoc()): ?>
            <form method="POST" action="crop_details.php" class="card-form">
                <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($crop['c_id']); ?>">
                <div class="product-card" onclick="submitForm(this);">
                    <div class="card-header">
                        <div class="product-icon">
                            <img src="<?php echo htmlspecialchars($crop['img_url']); ?>" alt="<?php echo htmlspecialchars($crop['c_name']); ?> Icon" />
                        </div>
                        <div class="shelf-life">
                            <?php echo htmlspecialchars($crop['shelf_life']); ?> days
                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="product-title">
                            <?php if ($crop['review_count'] > 0): ?>
                                <span class="rating"><?php echo number_format($crop['avg_rating'], 1); ?>★</span> <?php echo htmlspecialchars($crop['c_name']); ?> <span class="review-count">(<?php echo htmlspecialchars($crop['review_count']); ?>)</span>
                            <?php else: ?>
                                - ★ <?php echo htmlspecialchars($crop['c_name']); ?> <span class="review-count">(<?php echo htmlspecialchars($crop['review_count']); ?>)</span>
                            <?php endif; ?>
                        </h2>
                        <p class="product-price">₹<?php echo htmlspecialchars($crop['ppu']); ?>/<?php echo htmlspecialchars($crop['unit']); ?></p>
                        <p class="seller-name">Farmer: <?php echo htmlspecialchars($crop['farmer_name']); ?></p>
                    </div>
                </div>
            </form>
        <?php endwhile; ?>
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
    </script>
</body>
</html>

<?php
$conn->close();
?>