<?php
$host = "localhost";      // Server hostname (default is localhost for XAMPP)
$username = "root";       // Default XAMPP MySQL username
$password = "";           // Default XAMPP MySQL password is empty
$database = "college_event";  // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
