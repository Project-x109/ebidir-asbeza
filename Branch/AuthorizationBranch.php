<?php
if (!isset($_SESSION['role']))
    header("location:../index.php");
else if ($_SESSION['role'] != 'branch')
    header("location:" . $_SESSION['role'] . "/");
