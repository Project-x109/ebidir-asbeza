<?php
include "../connect.php"; // Include your database connection script
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin','EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
// Check if the 'id' is set in the session
if (isset($_SESSION['id'])) {
    // SQL query using prepared statement to retrieve loan data for the past 6 months
    $sql = "SELECT price, createdOn FROM loans WHERE YEAR(createdOn) = YEAR(CURRENT_DATE) AND MONTH(createdOn) >= MONTH(CURRENT_DATE) - 5";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loanData = array();

        while ($row = $result->fetch_assoc()) {
            $loanData[] = $row;
        }

        // Return loan data as JSON
        header('Content-Type: application/json');
        echo json_encode($loanData);
    } else {
        // Handle the case when no data is found
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['error' => 'Data not found']);
    }

    // Close the prepared statement and the database connection
    $stmt->close();
}

// Handle the case when 'id' is not set in the session
else {
    header('HTTP/1.0 404 Not Found');
    echo json_encode(['error' => 'Session id not found']);
}

// Close the database connection
$conn->close();
?>
