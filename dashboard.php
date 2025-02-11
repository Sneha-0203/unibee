<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>You are successfully logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
