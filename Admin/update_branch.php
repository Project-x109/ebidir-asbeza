<?php
include "../common/ratelimiter.php";
include "../connect.php";
include "../user/functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);



if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
}
$branchId = $_POST['branch_id'];
$location = $_POST['location'];

// Initialize response array
$response = array();

// Update branch data
$queryBranch = "UPDATE branch SET location = ? WHERE branch_id = ?";
$stmtBranch = $conn->prepare($queryBranch);
if (!$stmtBranch) {
    $response['status'] = 'error';
    $response['message'] = 'Error preparing the SQL statement for branch data update: ' . $conn->error;
} else {
    $stmtBranch->bind_param("ss", $location, $branchId);

    if ($stmtBranch->execute()) {
        $response['status'] = 'success';
        insertLog($conn, $_SESSION['id'], "The Location of Branch with user ID " . $branchId . " Have been Updated to " . $location);
    } else {
        // Branch data update failed
        $response['status'] = 'error';
        insertLog($conn, $_SESSION['id'], "Unable to update branch with branch ID " . $branchId);
        $response['message'] = 'Error updating branch data: ' . $stmtBranch->error;
    }

    $stmtBranch->close();
}

// Close database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
