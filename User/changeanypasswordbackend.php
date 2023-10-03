<?php
session_start();
include "../connect.php";
/* if (isset($_POST['change_old_password'])) {
    // User is in active status, allow them to change their password
    echo "Debug: 'change_old_password' is set<br>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION['id'];
        $oldPassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
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

        // Retrieve the user's current password hash from the database
        $getCurrentPasswordSql = "SELECT `password` FROM `users` WHERE `id` = '$userId'";
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

            // Update the user's password
            $updateSql = "UPDATE `users` SET `password` = '$hashedPassword' WHERE `id` = '$userId'";

            if ($conn->query($updateSql)) {
                // Password updated successfully
                $_SESSION['success'] = "Password updated successfully!";

                exit();
            } else {
                // Error updating password, store error message in session
                $_SESSION['error'] = "Error updating password: " . mysqli_error($conn);

                exit();
            }
        } else {
            $_SESSION['password_errors'] = $errors;
            header('Content-Type: application/json');
            exit();
        }
    }
} */


if (isset($_POST['change_old_password'])) {
    // User is in active status, allow them to change their password

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION['id'];
        $oldPassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
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

        // Retrieve the user's current password hash from the database
        $getCurrentPasswordSql = "SELECT `password` FROM `users` WHERE `id` = '$userId'";
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

            // Update the user's password
            $updateSql = "UPDATE `users` SET `password` = '$hashedPassword' WHERE `id` = '$userId'";

            if ($conn->query($updateSql)) {
                // Password updated successfully
                $response = array(
                    'success' => true,
                    'message' => 'Password updated successfully!'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            } else {
                // Error updating password, store error message in session
                $response = array(
                    'success' => false,
                    'message' => 'Error updating password: ' . mysqli_error($conn)
                );

                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        } else {
            $response = array(
                'success' => false,
                'message' => $errors
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }
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
