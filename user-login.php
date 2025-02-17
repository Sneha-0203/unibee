<?php
// user-login.php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'user';

            // Redirect to user dashboard or homepage
            header('Location: user-dashboard.html');
            exit;
        } else {
            echo '<p style="color:red;">Invalid password. Please try again.</p>';
        }
    } else {
        echo '<p style="color:red;">No user found with this email. Please check your email and try again.</p>';
    }
    $stmt->close();
}
?>
