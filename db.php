<?php
$servername = "localhost"; // Typically localhost for local development
$username = "root"; // MySQL username
$password = ""; // MySQL password (usually empty for local servers like XAMPP)
$dbname = "unibee"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error); // Stop execution and show error message
} else {
    echo 'Connected successfully'; // This will confirm the connection
}
?>
