<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details from the database
include 'db.php';
$query = "SELECT name, last_login FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Include HTML part
include 'admin-dashboard.html';
?>
