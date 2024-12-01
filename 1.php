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
    $sql_crops = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND (crop.c_name LIKE ? OR farmer.name LIKE ?) AND is_approved=1
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt = $conn->prepare($sql_crops);
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param('iss', $f_id, $search_term, $search_term);
    $stmt->execute();
    $result_crops = $stmt->get_result();

    $sql_crops1 = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND (crop.c_name LIKE ? OR farmer.name LIKE ?) AND is_approved=0
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt1 = $conn->prepare($sql_crops1);
    $search_term1 = '%' . $search_query . '%';
    $stmt1->bind_param('iss', $f_id, $search_term1, $search_term1);
    $stmt1->execute();
    $result_crops1 = $stmt1->get_result();

    $sql_crops2 = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND (crop.c_name LIKE ? OR farmer.name LIKE ?) AND is_approved=-1
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt2 = $conn->prepare($sql_crops2);
    $search_term2 = '%' . $search_query . '%';
    $stmt2->bind_param('iss', $f_id, $search_term2, $search_term2);
    $stmt2->execute();
    $result_crops2 = $stmt2->get_result();


} else {
    $sql_crops = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND is_approved=1
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt = $conn->prepare($sql_crops);
    $stmt->bind_param('i', $f_id);
    $stmt->execute();
    $result_crops = $stmt->get_result();


    $sql_crops1 = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND is_approved=0
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt1 = $conn->prepare($sql_crops1);
    $stmt1->bind_param('i', $f_id);
    $stmt1->execute();
    $result_crops1 = $stmt1->get_result();


    $sql_crops2 = "SELECT crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name AS farmer_name,
                  AVG(review.rating) AS avg_rating, COUNT(review.r_id) AS review_count, COUNT(orders.order_id) AS order_count
                  FROM crop
                  JOIN farmer ON crop.f_id = farmer.f_id
                  LEFT JOIN review ON crop.c_id = review.c_id
                  LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
                  WHERE crop.f_id = ? AND is_approved=-1
                  GROUP BY crop.c_id, crop.c_name, crop.c_qty, crop.img_url, crop.ppu, crop.unit, crop.shelf_life, farmer.name";
    $stmt2 = $conn->prepare($sql_crops2);
    $stmt2->bind_param('i', $f_id);
    $stmt2->execute();
    $result_crops2 = $stmt2->get_result();
}

// Fetch farmer's stats
$sql_stats = "SELECT 
                COUNT(orders.order_id) AS total_delivered,
                AVG(review.rating) AS avg_rating
              FROM crop
              LEFT JOIN review ON crop.c_id = review.c_id
              LEFT JOIN orders ON crop.c_id = orders.c_id AND orders.status = 'Completed'
              WHERE crop.f_id = $f_id ";
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
    <style>
        .sections {
    display: flex;
    margin-bottom: 20px;
}

.section-btn {
    flex: 1;
    padding: 10px;
    border: none;
    background-color: #f0f0f0;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}

.section-btn.active {
    background-color: #4caf50;
    color: white;
}

.section-content {
    display: none;
}

.section-content.active {
    display: block;
}

    </style>
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

<div class="sections">
    <button class="section-btn active" onclick="showSection('your-crops')">Your Crops</button>
    <button class="section-btn" onclick="showSection('pending-crops')">Pending Requests</button>
    <button class="section-btn" onclick="showSection('rejected-crops')">Rejected Requests</button>
</div>

<!-- Section Contents -->
<div id="your-crops" class="section-content">
    <div class="crop-cards">
        <?php while ($crop = $result_crops->fetch_assoc()): ?>
            <form method="POST" action="crop_details.php" class="card-form">
                <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($crop['c_id']); ?>">
                <div class="card" onclick="submitForm(this);">
                    <div class="card-header">
                        <div class="product-icon">
                            <img src="<?php echo htmlspecialchars($crop['img_url']); ?>" alt="<?php echo htmlspecialchars($crop['c_name']); ?> Icon" />
                        </div>
                        <div class="shelf-life"><?php echo htmlspecialchars($crop['shelf_life']); ?> days</div>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($crop['c_name']); ?></h3>
                        <p>₹<?php echo htmlspecialchars($crop['ppu']); ?>/<?php echo htmlspecialchars($crop['unit']); ?></p>
                        <p><?php echo ($crop['review_count'] > 0) ? number_format($crop['avg_rating'], 1) . "★ ({$crop['review_count']} reviews)" : "Not reviewed yet"; ?></p>
                        <p><?php echo htmlspecialchars($crop['order_count']); ?> orders</p>
                    </div>
                </div>
            </form>
        <?php endwhile; ?>
    </div>
</div>

<div id="pending-crops" class="section-content" >
    <div class="crop-cards">
     <?php while ($crop = $result_crops1->fetch_assoc()): ?>
            <form method="POST" action="crop_details.php" class="card-form">
                <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($crop['c_id']); ?>">
                <div class="card" onclick="submitForm(this);">
                    <div class="card-header">
                        <div class="product-icon">
                            <img src="<?php echo htmlspecialchars($crop['img_url']); ?>" alt="<?php echo htmlspecialchars($crop['c_name']); ?> Icon" />
                        </div>
                        <div class="shelf-life"><?php echo htmlspecialchars($crop['shelf_life']); ?> days</div>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($crop['c_name']); ?></h3>
                        <p>₹<?php echo htmlspecialchars($crop['ppu']); ?>/<?php echo htmlspecialchars($crop['unit']); ?></p>
                        <p><?php echo ($crop['review_count'] > 0) ? number_format($crop['avg_rating'], 1) . "★ ({$crop['review_count']} reviews)" : "Not reviewed yet"; ?></p>
                        <p><?php echo htmlspecialchars($crop['order_count']); ?> orders</p>
                    </div>
                </div>
            </form>
        <?php endwhile; ?>
        </div>
</div>

<div id="rejected-crops" class="section-content" style="display: none;">
    <div class="crop-cards">
     <?php while ($crop = $result_crops2->fetch_assoc()): ?>
            <form method="POST" action="crop_details.php" class="card-form">
                <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($crop['c_id']); ?>">
                <div class="card" onclick="submitForm(this);">
                    <div class="card-header">
                        <div class="product-icon">
                            <img src="<?php echo htmlspecialchars($crop['img_url']); ?>" alt="<?php echo htmlspecialchars($crop['c_name']); ?> Icon" />
                        </div>
                        <div class="shelf-life"><?php echo htmlspecialchars($crop['shelf_life']); ?> days</div>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($crop['c_name']); ?></h3>
                        <p>₹<?php echo htmlspecialchars($crop['ppu']); ?>/<?php echo htmlspecialchars($crop['unit']); ?></p>
                        <p><?php echo ($crop['review_count'] > 0) ? number_format($crop['avg_rating'], 1) . "★ ({$crop['review_count']} reviews)" : "Not reviewed yet"; ?></p>
                        <p><?php echo htmlspecialchars($crop['order_count']); ?> orders</p>
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
    </script>
</body>
</html>

<?php
$conn->close();
?>
<script>
    function showSection(sectionId) {
        // Hide all sections
        const sections = document.querySelectorAll('.section-content');
        sections.forEach(section => section.style.display = 'none');

        // Remove active class from all buttons
        const buttons = document.querySelectorAll('.section-btn');
        buttons.forEach(button => button.classList.remove('active'));

        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';

        // Add active class to the corresponding button
        const activeButton = document.querySelector(`[onclick="showSection('${sectionId}')"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    // Show 'Your Crops' section by default
    document.getElementById('your-crops').style.display = 'block';
</script>
