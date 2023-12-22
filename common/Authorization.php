<?php
// Check if the user is logged in
include "../common/jwt.php";
require '../vendor/firebase/php-jwt/src/JWT.php';
if (!isset($_SESSION['role'])) {
    header("location: ../index.php");
    exit();
} else {
    $allowedRoles = array('Admin', 'user', 'branch', "EA", "delivery");
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        $role = $_SESSION['role'] == "EA" ? "Admin" : $_SESSION['role'];
        $role = $_SESSION['role'] == "delivery" ? "branch" : $_SESSION['role'] . "/";
        header("location:$role/");
        exit();
    }
}
if (!isset($_COOKIE['jwt_token']) || isTokenExpired($_COOKIE['jwt_token'])) {
    session_destroy();
    $_SESSION['error'] = "Your Session Has ended please login again";
    header("Location: ../index.php");
    exit();
}
function checkAuthorization($requiredRoles)
{
    // Start a session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is logged in
    if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
        header("Location: ../pages-misc-error.php");
        exit();
    }

    // Check if the user's role is in the required roles
    if (!in_array($_SESSION['role'], $requiredRoles)) {
        $_SESSION['error'] = "You are not authorized to access this page.";
        header("Location: ../pages-misc-error.php");
        exit();
    }
}
