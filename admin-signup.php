<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin details from form
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert admin details into database
$sql = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    echo "<script>alert('Signup successful! Please login now.'); window.location.href='admin-auth.html';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='admin-auth.html';</script>";
}

$stmt->close();
$conn->close();
?>
