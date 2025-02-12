<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM admins WHERE reset_token='$token' AND reset_expires > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $update_sql = "UPDATE admins SET password='$hashed_password', reset_token=NULL, reset_expires=NULL WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            echo "Password updated successfully!";
            header("Location: admin-auth.html"); // Redirect to admin login page
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Invalid or expired token!";
    }
}

$conn->close();
?>
