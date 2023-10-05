<?php
include "../connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated user data from the form
    $userId = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $dob = $_POST["dob"];
    $status = $_POST["status"];
    $TIN_Number = $_POST["TIN_Number"];
    $phone = $_POST["phone"];


    // Perform the database update
    $sql = "UPDATE users SET name='$name', email='$email', dob='$dob', status='$status', TIN_Number='$TIN_Number',phone='$phone' WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        // Update successful
        echo json_encode(["status" => "success"]);
    } else {
        // Update failed
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle the case where this script is accessed without a POST request
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
