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

    // Check if the admin exists
    $sql = "SELECT * FROM admins WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the token in the database
        $update_sql = "UPDATE admins SET reset_token=?, reset_expiry=? WHERE email=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Send the reset email
        $reset_link = "http://localhost/unibee/admin-reset-password.php?token=" . $token;
        $subject = "Admin Password Reset Request";
        $message = "Click this link to reset your password: $reset_link";
        $headers = "From: no-reply@unibee.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Password reset link sent to your email.'); window.location.href='admin-auth.html';</script>";
        } else {
            echo "<script>alert('Failed to send email.'); window.location.href='admin-forgot-password.html';</script>";
        }
    } else {
        echo "<script>alert('Admin email not found.'); window.location.href='admin-forgot-password.html';</script>";
    }
}

$conn->close();
?>
