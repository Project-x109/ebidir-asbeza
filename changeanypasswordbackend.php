<?php
include "./connect.php";
session_start();
/* include "./common/Authorization.php"; */
include "./user/functions.php";
include "./common/jwt.php";
require './vendor/firebase/php-jwt/src/JWT.php';
if (!isset($_SESSION['role'])) {
    header("location: ../index.php");
    exit();
} else {
    $allowedRoles = array('Admin', 'user', 'branch', "EA", "delivery");
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        // Redirect based on the user's role
        $role = $_SESSION['role'] == "EA" ? "Admin" : $_SESSION['role'];
        $role = $_SESSION['role'] == "delivery" ? "branch" : $_SESSION['role'] . "/";
        header("location:$role/");
        exit();
    }
}

if (!isset($_COOKIE['jwt_token']) || isTokenExpired($_COOKIE['jwt_token'])) {
    // Token is missing or has expired, handle unauthorized access
    session_destroy();
    // Set an error message
    $_SESSION['error'] = "Unauthorized access. Please log in again.";
    // Redirect to the login page
    header("Location: ./common/pages-misc-error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID from the session
    $userId = $_SESSION['id'];

    // Sanitize and validate input
    $oldPassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
    $minPasswordLength = 8;
    $uppercaseRequired = true;
    $lowercaseRequired = true;
    $numberRequired = true;
    $specialCharRequired = true;
    $errors = array();
    $response = array();

    if (strlen($newPassword) < $minPasswordLength) {
        $errors[] = "Password must be at least $minPasswordLength characters long.";
    }

    if ($uppercaseRequired && !preg_match('/[A-Z]/', $newPassword)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }

    if ($lowercaseRequired && !preg_match('/[a-z]/', $newPassword)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }

    if ($numberRequired && !preg_match('/[0-9]/', $newPassword)) {
        $errors[] = "Password must contain at least one number.";
    }

    if ($specialCharRequired && !preg_match('/[\W_]/', $newPassword)) {
        $errors[] = "Password must contain at least one special character.";
    }
    $getCurrentPasswordSql = "SELECT `password` FROM `users` WHERE `user_id` = '" . $userId . "'";
    $result = $conn->query($getCurrentPasswordSql);
    if ($result && $row = $result->fetch_assoc()) {
        $currentPasswordHash = $row['password'];

        // Verify the old password
        if (!password_verify($oldPassword, $currentPasswordHash)) {
            $errors[] = "Old password is incorrect.";
        }

        // Check if the new password is the same as the old password
        if (password_verify($newPassword, $currentPasswordHash)) {
            $errors[] = "New password cannot be the same as the old password.";
        }
    } else {
        // Unable to fetch the current password, handle the error as needed
        $errors[] = "Error fetching the current password.";
    }

    // Check if "New Password" and "Confirm Password" match
    if ($newPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE `users` SET `password` = '$hashedPassword' WHERE `user_id` = '" . $userId . "'";
        if ($conn->query($updateSql)) {
            insertLog($conn, $_SESSION['id'], "Password Has been changed for User with id {$_SESSION['id']}");
            $response = array(
                'success' => true,
                'message' => 'Password updated successfully!'
            );
        } else {
            // Error updating password
            $response = array(
                'success' => false,
                'message' => 'Error updating password: ' . mysqli_error($conn)
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => $errors
        );
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
function isValidPassword($password)
{
    // Check if the password meets the requirements
    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return false;
    }

    if (!preg_match("/[a-z]/", $password)) {
        return false;
    }

    if (!preg_match("/[0-9]/", $password)) {
        return false;
    }

    // All requirements met
    return true;
}
