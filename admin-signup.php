<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (email, password) VALUES ('$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo 'Admin registered successfully.';
    } else {
        echo 'Error: ' . $conn->error;
    }
}
?>
