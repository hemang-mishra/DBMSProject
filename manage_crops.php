<?php
session_start();
include("db_connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$f_id = $_SESSION['user_id']; // Get farmer's ID from session

// Get the crop ID from the query parameter
$cid = isset($_GET['cid']) ? $_GET['cid'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $c_name = $_POST['c_name'];
    $c_qty = $_POST['c_qty'];
    $img_url = $_POST['img_url'];
    $ppu = $_POST['ppu'];
    $unit = $_POST['unit'];
    $shelf_life = $_POST['shelf_life'];
    $c_desc = $_POST['c_desc'];
    // $is_available = isset($_POST['is_available']) ? 1 : 0;
    $is_available = $_POST['is_available'];

    // Update the crop in the database
    $sql = "UPDATE crop SET c_name = ?, c_qty = ?, img_url = ?, ppu = ?, unit = ?, shelf_life = ?, c_desc = ?, is_available = ? WHERE c_id = ? AND f_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisdsissii", $c_name, $c_qty, $img_url, $ppu, $unit, $shelf_life, $c_desc, $is_available, $cid, $f_id);
    if ($stmt->execute()) {
        // Redirect to crop_details.php with POST request after successful update
        echo "<form id='redirectForm' method='POST' action='crop_details.php'>
                <input type='hidden' name='c_id' value='$cid'>
              </form>
              <script type='text/javascript'>
                document.getElementById('redirectForm').submit();
              </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch the crop details
$sql = "SELECT * FROM crop WHERE c_id = ? AND f_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cid, $f_id);
$stmt->execute();
$result = $stmt->get_result();
$crop = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Crop</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/manage_crops.css"> <!-- Link to external CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include("header_f.php"); ?>
    <div class="manage-crop-section">
        <div class="manage-crop-card">
            <h2>Update Crop Details</h2>
            <form action="manage_crops.php?cid=<?php echo $cid; ?>" method="POST">
                <div class="input-group">
                    <label for="c_name">Crop Name:</label>
                    <input type="text" id="c_name" name="c_name" value="<?php echo htmlspecialchars($crop['c_name']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="c_qty">Quantity:</label>
                    <input type="number" id="c_qty" name="c_qty" value="<?php echo htmlspecialchars($crop['c_qty']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="img_url">Image URL (optional):</label>
                    <input type="text" id="img_url" name="img_url" value="<?php echo htmlspecialchars($crop['img_url']); ?>">
                </div>

                <div class="input-group">
                    <label for="ppu">Price per Unit (₹):</label>
                    <input type="number" id="ppu" name="ppu" value="<?php echo htmlspecialchars($crop['ppu']); ?>" required step="0.01">
                </div>

                <div class="input-group">
                    <label for="unit">Unit of Measurement:</label>
                    <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($crop['unit']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="shelf_life">Shelf Life (days):</label>
                    <input type="number" id="shelf_life" name="shelf_life" value="<?php echo htmlspecialchars($crop['shelf_life']); ?>">
                </div>

                <div class="input-group">
                    <label for="c_desc">Description:</label>
                    <textarea id="c_desc" name="c_desc" rows="4"><?php echo htmlspecialchars($crop['c_desc']); ?></textarea>
                </div>

                <div class="input-group">
                    <label>Available for Sale:</label>
                    <div class="availability-cards">
                        <label class="availability-card">
                            <input type="radio" name="is_available" value="1" <?= (isset($_POST['is_available']) &&  $_POST['is_available']) ? 'checked' : '' ?> required>
                            <div class="card-content">
                                <img src="assets/available.png" alt="Available">
                                <span>Available</span>
                            </div>
                        </label>
                        <label class="availability-card">
                            <input type="radio" name="is_available" value="0" <?= (isset($_POST['is_available']) &&  !$_POST['is_available']) ? 'checked' : '' ?> required>
                            <div class="card-content">
                                <img src="assets/not_available.png" alt="Not Available">
                                <span>Not Available</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="primary-button">Update Crop</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>