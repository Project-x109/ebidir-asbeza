<?php
// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header("location: ../index.php");
} else {
    $allowedRoles = array('Admin', 'user', 'branch',"EA");
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        // Redirect based on the user's role
        $role=$_SESSION['role']=="EA"?"Admin":$_SESSION['role'];
        header("location:$role/");
    }
}
?>
