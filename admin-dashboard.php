<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If not logged in, redirect to the login page
    header('Location: admin-login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin data (if needed) from the database
include 'db.php';
$query = "SELECT * FROM admins WHERE id = $admin_id";
$result = $conn->query($query);
$admin = $result->fetch_assoc();
?>
