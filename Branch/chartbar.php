<?php

include "../connect.php";
session_start();

// Check if the session variable 'id' is set
if (isset($_SESSION['id'])) {
    // Retrieve data for the last two years
    $currentYear = date("Y");
    $lastYear = $currentYear - 1;

    // SQL query to fetch loan data for the last two years
    $sql = "SELECT YEAR(createdOn) AS loanYear, MONTH(createdOn) AS loanMonth, COUNT(*) AS loanCount 
            FROM loans 
            WHERE provider = ? 
            AND YEAR(createdOn) BETWEEN ? AND ?
            GROUP BY YEAR(createdOn), MONTH(createdOn)
            ORDER BY YEAR(createdOn), MONTH(createdOn)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sii", $_SESSION['id'], $lastYear, $currentYear);

    // Execute the query
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $loanData = array();

        while ($row = $result->fetch_assoc()) {
            $loanYear = $row['loanYear'];
            $loanMonth = $row['loanMonth'];
            $loanCount = $row['loanCount'];

            // Store loan count data in an associative array
            $loanData[] = array(
                'year' => $loanYear,
                'month' => $loanMonth,
                'count' => $loanCount
            );
        }

        // Return the loan data as JSON
        header('Content-Type: application/json');
        echo json_encode(['loanData' => $loanData]);
        exit; // Ensure that no other output is sent
    } else {
        // Handle the case when no data is found
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['error' => 'Data not found']);
    }
} else {
    // Handle invalid or missing session variable
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(['error' => 'Invalid session']);
}

// Close the database connection
$conn->close();
?>
