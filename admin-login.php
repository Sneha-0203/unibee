<?php
session_start(); // Start session for admin login

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

// Check if admin exists
$sql = "SELECT * FROM admins WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_email'] = $row['email'];

        // Redirect to admin dashboard
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='admin-login.html';</script>";
    }
} else {
    echo "<script>alert('No admin found!'); window.location.href='admin-login.html';</script>";
}

// Close connection
$conn->close();
?>
