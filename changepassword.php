<?php
session_start();
include "connect.php";
//changepassword
if (isset($_SESSION['status']) && $_SESSION['status'] === 'waiting' && isset($_POST['new_password'])) {
    // User is in waiting status, allow them to change their password
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
        $userId = $_SESSION['id'];
        $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

        // Define password validation rules
        $minPasswordLength = 8;
        $uppercaseRequired = true;
        $lowercaseRequired = true;
        $numberRequired = true;
        $specialCharRequired = true;

        // Password complexity check
        $errors = array();

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
        // Retrieve the user's current password from the database
        $getCurrentPasswordSql = "SELECT `password` FROM `users` WHERE `id` = '$userId'";
        $result = $conn->query($getCurrentPasswordSql);
        if ($result && $row = $result->fetch_assoc()) {
            $currentPasswordHash = $row['password'];

            // Check if the new password matches the current password
            if (password_verify($newPassword, $currentPasswordHash)) {
                $errors[] = "New password cannot be the same as the current password.";
            }
        }
        // Check if "New Password" and "Confirm Password" match
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        if (empty($errors)) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password and set status to 'active'
            $updateSql = "UPDATE `users` SET `password` = '$hashedPassword', `status` = 'active' WHERE `id` = '$userId'";
            if ($conn->query($updateSql)) {
                unset($_SESSION['on_newpassword_page']);
                // Password updated successfully, redirect to the dashboard
                $_SESSION['success'] = "Password updated successfully!";
                $loc = $_SESSION['role'] . "/";
                header("location: " . $loc);
                exit();
            } else {
                // Error updating password, store error message in session
                $_SESSION['error'] = "Error updating password: " . mysqli_error($conn);
                header("location: newpassword.php");
                exit();
            }
        } else {
            $_SESSION['password_errors'] = $errors;
            header("location: newpassword.php");
            exit();
        }
    }
} else {
    // If the user is not in waiting status or there were validation errors, redirect to the appropriate dashboard
    $loc = $_SESSION['role'] . "/";
    header("location: " . $loc);
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

?>