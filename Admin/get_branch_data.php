<?php
include "../connect.php";
session_start();
include "./AuthorizationAdmin.php";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query to retrieve branch data based on user_id
    $sql = "SELECT * FROM branch WHERE branch_id = '$user_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $branchData = $result->fetch_assoc();
        echo json_encode($branchData);
    } else {
        echo json_encode(['error' => 'Branch not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
