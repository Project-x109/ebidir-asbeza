<?php
include "../connect.php"; // Include your database connection script

// Get the provider ID from the session
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin','EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
// SQL query to retrieve loan data for the past 6 months
$sql = "SELECT price, status FROM loans";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $loanData = array(
        'total' => 0,
        'paid' => 0,
        'unpaid' => 0,
        'pending' => 0
    );

    while ($row = $result->fetch_assoc()) {
        $loanData['total'] += $row['price'];

        // Check the status of each loan and update corresponding values
        switch ($row['status']) {
            case 'paid':
                $loanData['paid'] += $row['price'];
                break;
            case 'unpaid':
                $loanData['unpaid'] += $row['price'];
                break;
            case 'pending':
                $loanData['pending'] += $row['price'];
                break;
        }
    }

    // Return loan data as JSON
    header('Content-Type: application/json');
    echo json_encode($loanData);
} else {
    // Handle the case when no data is found
    header('HTTP/1.0 404 Not Found');
    $loanData = array(
        'total' => 0,
        'paid' => 0,
        'unpaid' => 0,
        'pending' => 0
    );
    echo json_encode($loanData);
}

// Close the database connection
$stmt->close();
$conn->close();
