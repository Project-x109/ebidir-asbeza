<?php
// Check if the user is logged in
include "../common/jwt.php";
require '../vendor/firebase/php-jwt/src/JWT.php';
if (!isset($_SESSION['role'])) {
    header("location: ../index.php");
} else {
    $allowedRoles = array('Admin', 'user', 'branch', "EA", "delivery");
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        // Redirect based on the user's role
        $role = $_SESSION['role'] == "EA" ? "Admin" : $_SESSION['role'];
        $role = $_SESSION['role'] == "delivery" ? "branch" : $_SESSION['role'] . "/";
        header("location:$role/");
    }
}
if (!isset($_COOKIE['jwt_token']) || isTokenExpired($_COOKIE['jwt_token'])) {
    // Token is missing or has expired, handle unauthorized access
    session_destroy();
    // Set an error message
    $_SESSION['error'] = "Your Session Has ended please login again";
    // Redirect to the login page
    header("Location: ../index.php");
    exit();
}
/* if (isset($_SESSION['error'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
            errorToast.show();
            document.querySelector("#error-toast .toast-body").innerHTML = "' . $_SESSION['error'] . '";
        });
      </script>';
    unset($_SESSION['error']); // Clear the error message

} */
function checkAuthorization($requiredRoles)
{
    // Start a session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is logged in
    if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
        // User is not logged in, redirect to the login page
        header("Location: ../common/pages-misc-error.php");
        exit();
    }

    // Check if the user's role is in the required roles
    if (!in_array($_SESSION['role'], $requiredRoles)) {
        // User doesn't have the required role, show an error or redirect to a different page
        $_SESSION['error'] = "You are not authorized to access this page.";
        header("Location: ../common/pages-misc-error.php");
        exit();
    }
}
