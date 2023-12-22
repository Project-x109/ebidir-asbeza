<?php
include "../connect.php";
include "../user/functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA');
checkAuthorization($requiredRoles);
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
} else if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM branch WHERE branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $branchData = $result->fetch_assoc();
        echo json_encode($branchData);
        insertLog($conn, $_SESSION['id'], "Featched branch data with branch ID " . $user_id);
    } else {
        echo json_encode(['error' => 'Branch not found']);
        insertLog($conn, $_SESSION['id'], "Failed to Fetch user data with user ID " . $userId);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
$conn->close();
