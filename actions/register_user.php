<?php
include('../db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = $_POST['role'];
    $isConsumer = ($role === 'consumer') ? 1 : 0;
    $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    // Validate pincode
    if (!preg_match('/^\d{6}$/', $pincode)) {
        $errors[] = "Pincode must be exactly 6 digits.";
    }

    if (empty($errors)) {
        // Generate a unique user_id
        $result = $conn->query("SELECT MAX(user_id) AS max_id FROM user");
        $row = $result->fetch_assoc();
        $user_id = $row['max_id'] + 1;

        // Insert into user table
        $query = "INSERT INTO user (user_id, username, isConsumer, password) VALUES ('$user_id', '$username', '$isConsumer', '$password')";
        if ($conn->query($query) === TRUE) {
            if ($isConsumer) {
                $query = "INSERT INTO consumer (u_id, uname, u_contact) VALUES ('$user_id', '$name', '$contact')";
            } else {
                $address = $address1 . ', ' . $address2 . ', ' . $city . ', ' . $state . ', ' . $pincode;
                $query = "INSERT INTO farmer (f_id, name, contact, address) VALUES ('$user_id', '$name', '$contact', '$address')";
            }

            if ($conn->query($query) === TRUE) {
                // Redirect to login.php on successful registration
                header("Location: ../login.php");
                exit;
            } else {
                $errors[] = "Error: " . $conn->error;
            }
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
    $conn->close();

    // Redirect back to the form with errors
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: ../index.php");
        exit;
    }
}
?>

