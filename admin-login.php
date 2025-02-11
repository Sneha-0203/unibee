<?php
session_start(); // Start the session

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

// Get admin credentials from form
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
        // Set session for admin
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_email'] = $row['email'];

        // Redirect to admin dashboard
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='admin-auth.html';</script>";
    }
} else {
    echo "<script>alert('No admin found!'); window.location.href='admin-auth.html';</script>";
}

$stmt->close();
$conn->close();
?>
