<?php
session_start(); // Start session to store user info

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

// Get user input
$email = $_POST['email'];
$password = $_POST['password'];

// Check if email exists
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if (password_verify($password, $row['password'])) { // Verify password
        $_SESSION['user_id'] = $row['id']; // Store session
        $_SESSION['user_name'] = $row['name']; // Store username
        
        header("Location: dashboard.php"); // Redirect to user dashboard
        exit(); // Stop further execution
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('No user found'); window.location.href='login.html';</script>";
}

$conn->close();
?>