<?php
// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header("location: ../index.php");
} else {
    $allowedRoles = array('Admin', 'user', 'branch');
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        // Redirect based on the user's role
        header("location: " . $_SESSION['role'] . "/");
    }
}
?>
