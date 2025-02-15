<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the users table
    $user_result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        if (password_verify($password, $user_row['password'])) {
            $_SESSION['user_id'] = $user_row['id']; // Store user info in session
            $_SESSION['role'] = 'user'; // Role as user
            header('Location: user-dashboard.php'); // Redirect to user dashboard
            exit;
        } else {
            echo 'Invalid password for user.';
        }
    } else {
        echo 'No user found.';
    }
}
?>
