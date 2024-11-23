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
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
     <?php include("header.php"); ?>
    

    
</body>
</html>

<?php
$conn->close();
?>
