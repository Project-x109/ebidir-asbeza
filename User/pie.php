<?php
// Include your database connection code here
include "../connect.php";
session_start();

// Define an SQL query to fetch loan status counts
$loanQuery = "SELECT
    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS paid_count,
    SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END) AS unpaid_count,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count
    FROM loans WHERE user_id = ?";

// Prepare the SQL statement
$stmt = $conn->prepare($loanQuery);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$providerId = $_SESSION['id'];

// Bind the parameter
$stmt->bind_param("s", $providerId);

// Execute the query
if ($stmt->execute()) {
    // Fetch the counts
    $result = $stmt->get_result();
    $loanCounts = $result->fetch_assoc();

    // Encode $loanCounts as JSON and return it
    header('Content-Type: application/json');
    echo json_encode($loanCounts);
} else {
    die("Error executing statement: " . $stmt->error);
}

// Close the prepared statement
$stmt->close();
