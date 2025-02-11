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

    // Check if the email exists in users or admins table
    $sql = "SELECT * FROM users WHERE email=? UNION SELECT * FROM admins WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate unique reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token in the database
        $update_sql = "UPDATE users SET reset_token=?, reset_expiry=? WHERE email=? 
                       UNION 
                       UPDATE admins SET reset_token=?, reset_expiry=? WHERE email=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssss", $token, $expiry, $email, $token, $expiry, $email);
        $stmt->execute();

        // Send email with reset link
        $reset_link = "http://localhost/unibee/reset-password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password: $reset_link";
        $headers = "From: no-reply@unibee.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Password reset link sent to your email.'); window.location.href='admin-auth.html';</script>";
        } else {
            echo "<script>alert('Failed to send email.'); window.location.href='forgot-password.html';</script>";
        }
    } else {
        echo "<script>alert('Email not registered.'); window.location.href='forgot-password.html';</script>";
    }
}

$conn->close();
?>
