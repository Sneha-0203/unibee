<?php
// Start or resume the session
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_unset();
session_destroy();

// Prevent caching to ensure logout works properly
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// Redirect to the admin login page
header('Location: admin-login.php');
exit;
?>
