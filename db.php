<?php
$host = "localhost";    // Your database host (usually "localhost" for XAMPP)
$user = "root";         // Your database username (default for XAMPP is "root")
$password = "";         // Your database password (default is empty for XAMPP)
$database = "culinary_experience";  // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
