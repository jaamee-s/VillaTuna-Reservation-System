<?php
// Database credentials
$host = "localhost";        // Change if not localhost
$user = "root";             // Your MySQL username
$password = "";             // Your MySQL password
$database = "db_reservation"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8");
?>