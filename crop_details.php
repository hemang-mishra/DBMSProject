<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashborad</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/crop_details.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <?php
    include('db_connection.php');

    $cid = 12;

    $query = "SELECT * FROM crop,farmer WHERE c_id='$cid' AND crop.f_id = farmer.f_id";

    $result = $conn->query($query);
    $row = $result->fetch_assoc();


    $crop_name = $row['c_name'];
    $farmer_id = $row['f_id'];
    $farmer_name = $row['name'];
    $crop_img = $row['img_url'];
    $price = $row['ppu'];
    $unit = $row['unit'];


    $query = "SELECT AVG(rating) as avg FROM review WHERE c_id = $cid";

    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $avg_rating = round($row['avg'], 2);


    $query = "SELECT COUNT(*) as one_star FROM review WHERE c_id = $cid AND rating = 1";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $one_star = $row['one_star'];

    $query = "SELECT COUNT(*) as two_star FROM review WHERE c_id = $cid AND rating = 2";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $two_star = $row['two_star'];

    $query = "SELECT COUNT(*) as three_star FROM review WHERE c_id = $cid AND rating = 3";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $three_star = $row['three_star'];

    $query = "SELECT COUNT(*) as four_star FROM review WHERE c_id = $cid AND rating = 4";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $four_star = $row['four_star'];

    $query = "SELECT COUNT(*) as five_star FROM review WHERE c_id = $cid AND rating = 5";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $five_star = $row['five_star'];

    // Include the header file
    $sum = $one_star + $two_star + $three_star + $four_star + $five_star;
    if ($sum == 0) $sum = 100;
    $one_prog = 100 * $one_star / $sum;
    $two_prog = 100 * $two_star / $sum;
    $three_prog = 100 * $three_star / $sum;
    $four_prog = 100 * $four_star / $sum;
    $five_prog = 100 * $five_star / $sum;
    include 'header.php';

    ?>


    <div class="top-container">

        <!-- Crop Details Section -->
        <div class="crop-details">
            <div class="image-section">
                <img src="<?php echo $crop_img ?>" alt="Crop Image">
            </div>
            <div class="details-section">
                <p><?php echo $farmer_name ?>'s</p>
                <h1><?php echo $crop_name ?></h1>
                <p class="price">₹<?php echo $price ?>/<?php echo $unit ?></p>
                <button class="add-to-cart">Add to Cart</button>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste vero nemo quod velit quasi eius molestiae! Veniam, iusto alias similique mollitia expedita impedit inventore consequuntur earum officiis! Laudantium fugit velit perferendis aut! Ea sequi assumenda voluptas aliquid corporis doloribus accusamus autem, in repellendus fugit. Tenetur, doloremque cum. Necessitatibus quaerat, laborum asperiores eaque error consectetur, quidem quisquam libero fugit enim doloremque iure at dignissimos aliquam nesciunt earum cupiditate ad magnam! Incidunt debitis suscipit ex, nesciunt labore libero fugit voluptate consequuntur modi qui? Illum, pariatur voluptates. Nobis quia nulla laboriosam perferendis quidem unde ipsam, laborum ad et deleniti a quisquam officia id!</p>

            </div>
        </div>
    </div>

    <div class="lower-container">
        <h2>Reviews and Ratings</h2>
        <div style="display: flex; gap: 20px">
            <div class="rating-value"><?php echo $avg_rating ?> <span class="material-icons star-icon">star</span></div>

            <div class="ratings">

                <div class="stars">
                    <p>5 ★ <span class="bar"><span style="width: <?php echo $five_prog ?>%;"></span></span> <?php echo $five_star ?></p>
                    <p>4 ★ <span class="bar"><span style="width: <?php echo $four_prog ?>%;"></span></span> <?php echo $four_star ?></p>
                    <p>3 ★ <span class="bar"><span style="width: <?php echo $three_prog ?>%;"></span></span> <?php echo $three_star ?></p>
                    <p>2 ★ <span class="bar"><span style="width: <?php echo $two_prog ?>%;"></span></span> <?php echo $two_star ?></p>
                    <p>1 ★ <span class="bar"><span style="width: <?php echo $one_prog ?>%;"></span></span> <?php echo $one_star ?></p>
                </div>
            </div>
            <!-- Reviews Section -->
            <section class="reviews">
                <?php

                $query = "SELECT * FROM review,consumer WHERE c_id = $cid AND review.u_id = consumer.u_id";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {

                    $username = $row['uname'];
                    $comment = $row['comment'];
                    $review_rating = $row['rating'];

                    echo '<div class="review-card">
                        <div class="review-header">
                            <span class="username">' . $username . '</span>
                            <span class="review-rating">' . $review_rating . ' ★</span>
                        </div>
                        <p class="review-text">' . $comment . '</p>
                    </div>';
                }

                ?>
                <!-- Add more review cards here -->
            </section>
        </div>


    </div>



</body>

</html>