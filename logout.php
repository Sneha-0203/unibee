<?php
// Start the session
session_start();

// Destroy the session to log the admin out
session_destroy();

// Redirect to the login page
header("Location: admin-login.html");
exit;
?>
