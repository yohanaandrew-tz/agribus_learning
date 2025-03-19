<?php
$host = "localhost";  // Change if using a remote database
$dbname = "Agribusiness_Learning";
$username = "root";   // Change to your database username
$password = "";       // Change to your database password

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Uncomment for debugging
// echo "Connected successfully";
?>
