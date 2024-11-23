<?php
// Database credentials
$host = 'localhost'; // Hostname or IP address of your database server
$user = 'root'; // MySQL username
<<<<<<< HEAD
$password = 'hemangmishra'; // MySQL password
$database = 'dbms_project'; // Name of the database
=======
$password = ''; // MySQL password


$database = 'dbmsproject'; // Name of the database
>>>>>>> efcd75e8ae00c3b0305c08e6ad75c874c8d9a052


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
