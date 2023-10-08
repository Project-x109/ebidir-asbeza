<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";

// Check if the CSRF token is present in the request headers
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
} else if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Use a prepared statement to retrieve branch data based on user_id
    $sql = "SELECT * FROM branch WHERE branch_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("s", $user_id); // Assuming branch_id is an integer

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $branchData = $result->fetch_assoc();
        echo json_encode($branchData);
    } else {
        echo json_encode(['error' => 'Branch not found']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the database connection
$conn->close();
