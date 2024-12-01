<?php
include("../db_connection.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../css/admin.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    

    <!-- Hero Section -->
    <div class="header">
        <h1>Welcome to GreenLeaf Admin Page!</h1>
    </div>

    <!-- Product Grid -->
    <div class="container">

        <div class="button-container">
            <div class="button-row">
                <button class="button" onclick="window.location.href='consumer_list.php'">Consumer List</button>
                <button class="button" onclick="window.location.href='farmer_list.php'">Farmer List</button>
            </div>
            <div class="button-row">
                <button class="button" onclick="window.location.href='crop_requests.php'">Approve Crops</button>
                <button class="button" onclick="window.location.href='../login.php'">Login as User</button>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>