<?php
include "../connect.php";
include "../user/functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
// Check if the CSRF token is present in the request headers
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
} else if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode($userData);
        insertLog($conn, $_SESSION['id'], "Featched user data with user ID " . $userId);
    } else {
        echo json_encode(['error' => 'User not found']);
        insertLog($conn, $_SESSION['id'], "Failed to Fetch user data with user ID " . $userId);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
$conn->close();
