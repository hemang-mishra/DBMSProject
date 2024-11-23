<?php
// Database credentials
$host = 'localhost'; // Hostname or IP address of your database server
$user = 'root'; // MySQL username
$password = ''; // MySQL password
$database = 'dbms'; // Name of the database

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Optional: Uncomment the following line for debugging connection success
// echo 'Connected successfully';

// Close connection when done
// $conn->close(); // Close only when your project is terminating
?>
