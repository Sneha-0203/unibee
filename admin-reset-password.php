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

// Debugging: Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['token'], $_POST['new_password'], $_POST['confirm_password'])) {
        die("Missing required fields!");
    }

    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match!");
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT email FROM admins WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $update_stmt = $conn->prepare("UPDATE admins SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            echo "Password updated successfully!";
            header("Location: admin-auth.html"); // Redirect to admin login
            exit();
        } else {
            die("Error updating password: " . $conn->error);
        }
    } else {
        die("Invalid or expired token!");
    }
} else {
    die("Invalid request method!");
}

$conn->close();
?>
