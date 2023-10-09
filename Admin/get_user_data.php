<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";

// Check if the CSRF token is present in the request headers
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
} else if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode($userData);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
$conn->close();
