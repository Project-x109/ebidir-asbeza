<?php
session_start();
include "connect.php";
$sql = "SELECT * FROM `users` WHERE phone='$_POST[phone]' and password='$_POST[password]'";
$result = $conn->query($sql);
if($result->num_rows)
{
$row=$result->fetch_assoc();
$_SESSION['role']=$row['role'];
$_SESSION['id']=$row['id'];
$_SESSION['dob']=$row['dob'];
echo $_SESSION['role'];
 
  $loc=$_SESSION['role']."/";
header("location:".$loc);
}else{
  $_SESSION['error']="Username or password is not correct";
  header("index.php");
}
?>