<?php
include "connect.php";
include "./user/functions.php";
session_start();
insertLog($conn, $_SESSION['id'], "User with id {$_SESSION['id']}  has logged out");
unset($_SESSION['role']);
unset($_SESSION['tokenjwt']);
session_destroy();
if (isset($_COOKIE['jwt_token'])) {
    setcookie('jwt_token', '', time() - 3600, '/');
}

// Redirect to the login page
header("location: index.php");
