<?php
session_start();
include('../db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Check if the user exists
    $query = "SELECT * FROM user WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Start the session and set session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['isConsumer'] = $row['isConsumer'];
            // echo htmlspecialchars($_SESSION['user_id']); 

            // Redirect to the appropriate dashboard
            if ($row['isConsumer']) {
                header("Location: ../consumer_dashboard.php");
            } else {
                header("Location: ../1.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $conn->close();
}
?>