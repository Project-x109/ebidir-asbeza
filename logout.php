<?php
// Start the session
session_start();

// Unset or remove session variables
unset($_SESSION['role']);
unset($_SESSION['tokenjwt']);

// Destroy the session
session_destroy();

// Clear the jwt_token cookie
if (isset($_COOKIE['jwt_token'])) {
    setcookie('jwt_token', '', time() - 3600, '/');
}

// Redirect to the login page
header("location: index.php");
