<?php
session_start();
include "connect.php";
$sql = "SELECT * FROM `users` WHERE phone='$_POST[phone]' and password='$_POST[password]'";
$result = $conn->query($sql);
if($result->num_rows)
{
$row=$result->fetch_assoc();
$_SESSION['role']=$row['role'];
switch($_SESSION['role']){
    case "Admin":
        $loc="Admin/index.php";
}
include $loc;
}else{
  $_SESSION['error']="Username or password is not correct";
  include "index.php";
}
?>