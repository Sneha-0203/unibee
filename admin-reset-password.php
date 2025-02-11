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
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verify token
    $sql = "SELECT * FROM admins WHERE reset_token=? AND reset_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the admin's password
        $update_sql = "UPDATE admins SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $new_password, $token);
        $stmt->execute();

        echo "<script>alert('Password updated successfully!'); window.location.href='admin-auth.html';</script>";
    } else {
        echo "<script>alert('Invalid or expired token.'); window.location.href='admin-forgot-password.html';</script>";
    }
}

$conn->close();
?>
