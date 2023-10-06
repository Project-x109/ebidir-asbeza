<?php
include "../connect.php";
include "../user/functions.php";
session_start();

// Get POST data
$userId = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$status = $_POST['status'];
$phone = $_POST['phone'];

// Initialize response array
$response = array();

// Create an array to store validation errors
$validationErrors = array();

// Check if the name is empty or invalid
if (!validateName($name)) {
    $validationErrors[] = "Name can only contain letters and spaces.";
}

// Check if the email is empty or invalid
if (!validateEmail($email)) {
    $validationErrors[] = "Invalid email address.";
}

// Check if the phone is empty or invalid
if (!validatePhone($phone)) {
    $validationErrors[] = "Invalid phone number format.";
}

// Check if there are validation errors
if (!empty($validationErrors)) {
    $response['status'] = 'error';
    $response['message'] = 'Validation error(s)';
    $response['errors'] = $validationErrors;
} else {
    // Check if the email is already registered by another user
    $emailQuery = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmtEmail = $conn->prepare($emailQuery);
    $stmtEmail->bind_param("si", $email, $userId);
    $stmtEmail->execute();
    $stmtEmail->store_result();
    
    // Check if the phone number is already registered by another user
    $phoneQuery = "SELECT id FROM users WHERE phone = ? AND id != ?";
    $stmtPhone = $conn->prepare($phoneQuery);
    $stmtPhone->bind_param("si", $phone, $userId);
    $stmtPhone->execute();
    $stmtPhone->store_result();

    if ($stmtEmail->num_rows > 0) {
        $validationErrors[] = "Email is already registered by another user.";
    }

    if ($stmtPhone->num_rows > 0) {
        $validationErrors[] = "Phone number is already registered by another user.";
    }

    if (!empty($validationErrors)) {
        $response['status'] = 'error';
        $response['message'] = 'Validation error(s)';
        $response['errors'] = $validationErrors;
    } else {
        // Update user data
        $queryUser = "UPDATE users SET name = ?, email = ?, status = ?, phone = ? WHERE id = ?";
        $stmtUser = $conn->prepare($queryUser);

        if (!$stmtUser) {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing the SQL statement for user data update: ' . $conn->error;
        } else {
            $stmtUser->bind_param("ssssi", $name, $email, $status, $phone, $userId);

            if ($stmtUser->execute()) {
                // User data updated successfully
                $response['status'] = 'success';
            } else {
                // User data update failed
                $response['status'] = 'error';
                $response['message'] = 'Error updating user data: ' . $stmtUser->error;
            }

            $stmtUser->close();
        }
    }

    $stmtEmail->close();
    $stmtPhone->close();
}

// Close database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
