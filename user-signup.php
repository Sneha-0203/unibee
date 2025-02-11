<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if the email already exists
$checkEmail = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($checkEmail);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Email already exists! Please login.'); window.location.href='login.html';</script>";
} else {
    // Insert new user
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Please login.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='signup.html';</script>";
    }
}

// Close connection
$conn->close();
?>
