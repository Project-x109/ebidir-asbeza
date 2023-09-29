<?php
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone']) && isset($_POST['password'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $userEnteredPassword = $_POST['password'];

    // Retrieve the hashed password from the database based on the phone number
    $sql = "SELECT * FROM `users` WHERE phone = '$phone'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            // Verify the user-entered plain text password against the retrieved hashed password
            if (password_verify($userEnteredPassword, $hashedPassword)) {
                $_SESSION['role'] = $row['role'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['dob'] = $row['dob'];
                echo $_SESSION['role'];

                $loc = $_SESSION['role'] . "/";
                header("location:" . $loc);
                exit();
            } else {
                // Pass an error message to JavaScript
                echo '<script>alert("Password is incorrect");</script>';
            }
        } else {
            // Pass an error message to JavaScript
            echo '<script>alert("User with this phone number does not exist");</script>';
        }
    } else {
        // Pass an error message to JavaScript
        echo '<script>alert("MySQL Error: ' . mysqli_error($conn) . '");</script>';
    }
} else {
    // Pass an error message to JavaScript
    echo '<script>alert("Invalid request method or missing data");</script>';
}
?>
