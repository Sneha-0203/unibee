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
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update admin password in database
    $sql = "UPDATE admins SET password='$newPassword', reset_token=NULL WHERE reset_token='$token'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Password reset successful! You can now <a href='admin-auth.html'>login</a>.";
    } else {
        echo "Error resetting password.";
    }
}
$conn->close();
?>

<!-- HTML Form -->
<form method="POST">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Update Password</button>
</form>
