<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin','EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);

// Check if the session variable 'id' is set
if (isset($_SESSION['id'])) {
    // Retrieve data based on selected year
    $year = $_GET['year'];

    // Initialize the SQL query
    $sql = "SELECT * FROM loans";

    // Initialize an array to store bind parameters and types
    $bind_params = array();
    $bind_types = "";

    // Add condition for selected year if it is not 'all'
    if ($year !== 'all') {
        $sql .= " WHERE YEAR(createdOn) = ?";
        $bind_params[] = &$year; // Add year value by reference
        $bind_types .= "s"; // Add parameter type for year
    }

    $stmt = $conn->prepare($sql);

    // Dynamically bind parameters based on the array
    if (!empty($bind_params)) {
        array_unshift($bind_params, $bind_types);
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Calculate the total amount of pending, paid, and unpaid loans
        $pendingAmount = 0;
        $paidAmount = 0;
        $unpaidAmount = 0;

        foreach ($data as $record) {
            if ($record['status'] === 'pending') {
                $pendingAmount += $record['price'];
            } elseif ($record['status'] === 'paid') {
                $paidAmount += $record['price'];
            } elseif ($record['status'] === 'unpaid') {
                $unpaidAmount += $record['price'];
            }
        }

        // Calculate the total amount of loans for the selected year
        $totalAmount = $pendingAmount + $paidAmount + $unpaidAmount;

        // Calculate the percentage for each status
        $pendingPercentage = $totalAmount > 0 ? ($pendingAmount / $totalAmount) * 100 : 0;
        $paidPercentage = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
        $unpaidPercentage = $totalAmount > 0 ? ($unpaidAmount / $totalAmount) * 100 : 0;

        // Return the data and percentages as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pendingPercentage' => $pendingPercentage,
            'paidPercentage' => $paidPercentage,
            'unpaidPercentage' => $unpaidPercentage,
        ]);
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
