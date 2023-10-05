<?php
session_start();
include "../connect.php";
include '../user/functions.php';
if(isset($_POST['user'])){
$user_id=$_POST['user'];
$sql="SELECT * FROM users where user_id='$user_id'";
$res=$conn->query($sql);
if($res->num_rows>0)
{
    $row=$res->fetch_assoc();
     $_SESSION['user_id']=$row['user_id'];
     header('location:userinfo.php');
}
else{
// send error message here 
}
}
if(isset($_POST['branch_checkout'])){
    $user_id=$_POST['user_id'];
$sql="SELECT * FROM users where user_id='$user_id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$date = date('Y-m-d h:i:s');
$sql="SELECT (personal.personal_score+economic.economic_score) as score from personal INNER JOIN economic on personal.user_id=economic.user_id WHERE personal.user_id='$_POST[user_id]'";
$res = $conn->query($sql);
$row1=$res->fetch_assoc();
$score=$row1['score'];
$sql2 = "INSERT INTO loans(`user_id`,`price`,`credit_score`,`createdOn`,`provider`) Values('$_POST[user_id]',$_POST[total_price],'$score','$date','$_SESSION[id]')";
$res = $conn->query($sql2);
if ($res) {
    $last_id = mysqli_insert_id($conn);
    $limit = $row['credit_limit'] - $_POST['total_price'];
    $sql = "update users set credit_limit=$limit WHERE user_id='$_POST[user_id]'";
    $res = $conn->query($sql);
    //// send success message here 
   
    $_SESSION['user_id']=$last_id;
    echo $sql;
    header("location:paymentdone.php");
}

}

?>