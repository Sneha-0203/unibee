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
    $sql = "SELECT * FROM users WHERE reset_token=? AND reset_expiry > NOW() 
            UNION 
            SELECT * FROM admins WHERE reset_token=? AND reset_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE users SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=? 
                       UNION 
                       UPDATE admins SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $new_password, $token, $new_password, $token);
        $stmt->execute();

        echo "<script>alert('Password updated successfully!'); window.location.href='admin-auth.html';</script>";
    } else {
        echo "<script>alert('Invalid or expired token.'); window.location.href='forgot-password.html';</script>";
    }
}

$conn->close();
?>
