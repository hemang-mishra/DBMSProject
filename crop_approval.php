<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Details</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/crop_details.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
    .button-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }

    .approve-btn {
        background-color: green;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .approve-btn:hover {
        background-color: darkgreen;
    }

    .disapprove-btn {
        background-color: red;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .disapprove-btn:hover {
        background-color: darkred;
    }
</style>
</head>

<body>
    <?php
    include('db_connection.php');

    // Get the crop ID from the POST request
    $cid = $_POST['c_id'];

    $query = "SELECT * FROM crop,farmer WHERE c_id='$cid' AND crop.f_id = farmer.f_id";

    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $crop_name = $row['c_name'];
    $farmer_id = $row['f_id'];
    $farmer_name = $row['name'];
    $crop_img = $row['img_url'];
    $price = $row['ppu'];
    $unit = $row['unit'];
    $shelf_life = $row['shelf_life'];
    $description = $row['c_desc'];
    $avl_qty = $row['c_qty'];
    include 'header.php';
    ?>

    <div class="top-container">
        <!-- Crop Details Section -->
        <div class="crop-details">
            <div class="image-section">
                <img src="<?php echo $crop_img ?>" alt="Crop Image">
            </div>
            <div class="details-section">
                <p><em><?php echo $farmer_name ?>'s</em></p>
                <h1><?php echo $crop_name ?></h1>
                <p class="price">₹<?php echo $price ?>/<?php echo $unit ?></p>
                <p><b>Shelf Life:</b> <?php echo $shelf_life?> days</p>
                <p><b>Available Quantity:</b> <?php echo $avl_qty; echo $unit;?></p>
                <p><em><?php echo $description?></em></p>
            </div>
        </div>
    </div>
    <div class="button-container">
    <button class="approve-btn" onclick="updateApprovalStatus(1)">Approve</button>
    <button class="disapprove-btn" onclick="updateApprovalStatus(-1)">Disapprove</button>
</div>
   
</body>
</html>


<script>
    function updateApprovalStatus(status) {
       const cropId = "<?php echo $cid; ?>"; // Get crop ID from PHP
    fetch('update_approval.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cropId, status }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            if (data.redirect) {
                window.location.href = data.redirect; // Redirect to the specified page
            }
        } else {
            alert('Failed to update approval status. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
    }
</script>