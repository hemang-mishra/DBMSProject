<?php
session_start();
include('../db_connection.php');


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convert the 'id' to an integer
    echo "The user ID to delete is: " . $id;
    // Check if the user exists

    $query = "DELETE FROM review WHERE u_id = $id";
    $result = $conn->query($query);

    $query = "DELETE FROM orders WHERE u_id = $id";
    $result = $conn->query($query);

    $query = "DELETE FROM cart WHERE u_id = $id";
    $result = $conn->query($query);


    $query = "DELETE FROM consumer WHERE u_id = $id";
    $result = $conn->query($query);

    // $query = "DELETE FROM farmer WHERE f_id = $id";
    // $result = $conn->query($query);

    // $query = "DELETE FROM user WHERE user_id = $id";
    // $result = $conn->query($query);
    $conn->close();
    // You can now use $id for further logic, like deleting the user
} else {
    echo "No user ID provided.";
}
?>