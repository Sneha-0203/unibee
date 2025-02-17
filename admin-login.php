<?php
// admin-login.php

// Set default admin credentials
$default_email = 'admin@unibee.com';
$default_password = 'admin123';

// Get JSON input from the request
$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validate credentials
if ($email === $default_email && $password === $default_password) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
