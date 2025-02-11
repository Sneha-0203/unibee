<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in admin table
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generate a secure token
        $sql = "UPDATE admins SET reset_token='$token' WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            $resetLink = "http://localhost/unibee/admin-reset-password.php?token=$token";
            mail($email, "Admin Password Reset", "Click this link to reset your password: $resetLink");
            echo "Check your email for the password reset link.";
        } else {
            echo "Error updating token.";
        }
    } else {
        echo "No admin found with this email.";
    }
}
$conn->close();
?>

<!-- HTML Form -->
<form method="POST">
    <input type="email" name="email" placeholder="Enter your registered email" required>
    <button type="submit">Reset Password</button>
</form>
