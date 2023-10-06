<?php
// session_check.php

// Check if the user should be redirected to newpassword.php
if (isset($_SESSION['on_newpassword_page']) && $_SESSION['on_newpassword_page']) {
    // Redirect them back to newpassword.php
    header("Location: newpassword.php");
    exit();
}
