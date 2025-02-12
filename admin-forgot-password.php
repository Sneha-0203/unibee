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
    $email = $_POST['email'];

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update_sql = "UPDATE admins SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            $reset_link = "http://localhost/unibee/admin-reset-password.html?token=$token";
            echo "Reset link: <a href='$reset_link'>$reset_link</a>";
        } else {
            echo "Error generating reset link!";
        }
    } else {
        echo "No admin found with this email!";
    }
}

$conn->close();
?>
