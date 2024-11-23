<?php
include('../db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $isConsumer = ($_POST['role'] == 'consumer') ? 1 : 0;
    $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    // Generate a unique user_id
    $result = $conn->query("SELECT MAX(user_id) AS max_id FROM user");
    $row = $result->fetch_assoc();
    $user_id = $row['max_id'] + 1;

    // Insert the new user into the database
    $query = "INSERT INTO user (user_id, username, isConsumer, password) VALUES ('$user_id', '$username', '$isConsumer', '$password')";
    if ($conn->query($query) === TRUE) {
        if ($isConsumer) {
            $query = "INSERT INTO consumer (u_id, uname, u_contact) VALUES ('$user_id', '$name', '$contact')";
        } else {
            $address = $address1 . ', ' . $address2 . ', ' . $city . ', ' . $state . ', ' . $pincode;
            $query = "INSERT INTO farmer (f_id, name, contact, address) VALUES ('$user_id', '$name', '$contact', '$address')";
        }
        if ($conn->query($query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>