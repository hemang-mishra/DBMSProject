<?php
session_start();
include('../db_connection.php');


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convert the 'id' to an integer
    echo "The farmer ID to delete is: " . $id;
    // Check if the user exists

    $query = "DELETE FROM review WHERE c_id IN (SELECT c_id FROM crop WHERE crop.f_id = $id)";
    $result = $conn->query($query);

    $query = "DELETE FROM crop WHERE f_id = $id";
    $result = $conn->query($query);

    $query = "DELETE FROM farmer WHERE f_id = $id";
    $result = $conn->query($query);


    header("Location: ../admin/farmer_list.php");
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