<?php
include "../connect.php"; // Include your database connection script

// SQL query to retrieve loan data for the past 6 months for all providers
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
    echo json_encode(['error' => 'Data not found']);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
