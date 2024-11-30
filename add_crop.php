<?php
session_start();
include("db_connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$f_id = $_SESSION['user_id']; // Get farmer's ID from session

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $c_name = $_POST['c_name'];
    $c_qty = $_POST['c_qty'];
    $img_url = $_POST['img_url'];
    $ppu = $_POST['ppu'];
    $unit = $_POST['unit'];
    $shelf_life = $_POST['shelf_life'];
    $c_desc = $_POST['c_desc'];

    // Insert the new crop into the database
    $sql = "INSERT INTO crop (c_name, c_qty, img_url, ppu, unit, shelf_life, f_id, c_desc) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisdsiss", $c_name, $c_qty, $img_url, $ppu, $unit, $shelf_life, $f_id, $c_desc);
    if ($stmt->execute()) {
        echo "Crop added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/add_crop.css"> <!-- Link to external CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include("header.php"); ?>
    <div class="add-crop-section">
    <h2>Add New Crop</h2>
    <form action="submit_crop.php" method="POST">
        <label for="c_name">Crop Name:</label>
        <input type="text" id="c_name" name="c_name" placeholder="Enter crop name" required>

        <label for="c_qty">Quantity:</label>
        <input type="number" id="c_qty" name="c_qty" placeholder="Enter crop quantity" required>

        <label for="img_url">Image URL (optional):</label>
        <input type="text" id="img_url" name="img_url" placeholder="http://example.com/image.jpg">

        <label for="ppu">Price per Unit (₹):</label>
        <input type="number" id="ppu" name="ppu" placeholder="Enter price per unit" required step="0.01">

        <label for="unit">Unit of Measurement:</label>
        <input type="text" id="unit" name="unit" placeholder="e.g., kg, bunch" required>

        <label for="shelf_life">Shelf Life (days):</label>
        <input type="number" id="shelf_life" name="shelf_life" placeholder="Enter shelf life in days">

        <label for="c_desc">Description:</label>
        <textarea id="c_desc" name="c_desc" placeholder="Provide a description of the crop" rows="4"></textarea>

        <input type="hidden" name="f_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- Farmer ID taken from session -->

        <button type="submit">Add Crop</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>