<?php
session_start();
include 'db.php';
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);
    
    if (mysqli_num_rows($result) > 0) {
        // Redirect to login page if account exists
        header("Location: user-login.html?status=exists");
        exit();
    }

    // Insert query for new user
    $query = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$password')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.html?status=success");
        exit(); 
    } else {
        header("Location: user-signup.html?status=error");
        exit();
    }
}
ob_end_flush();
?>
