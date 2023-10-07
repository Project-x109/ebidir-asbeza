<?php
if (!isset($_SESSION['role']))
    header("location:../index.php");
else if ($_SESSION['role'] != 'user')
    header("location:" . $_SESSION['role'] . "/");
