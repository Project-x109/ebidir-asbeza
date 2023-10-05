<?php
include "../connect.php";
session_start();

// Get POST data
$userId = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$status = $_POST['status'];
$phone = $_POST['phone'];

// Initialize response array
$response = array();

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

// Close database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
