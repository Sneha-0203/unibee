<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Admin signup successful!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
