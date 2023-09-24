 
<?php
include "../connect.php";
session_start();
if(isset($_POST['add_personal'])){

    $sql="INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`,`user_id`) 
    VALUES ('$_POST[Number_of_dependents]','$_POST[Marriage_Status]','$_POST[Educational_Status]','$_POST[Criminal_record]','$_POST[id]')";
echo $sql;
    $res=$conn->query($sql);
if($res){
$_SESSION['success']="Personal information created Successfully";
    header("location:economic.php");
}
}
if(isset($_POST['add_economic'])){

    $sql="INSERT INTO `economic`(`field_of_employeement`, `number_of_income`, `year`, `branch`,`user_id`,`position`,`salary`) 
    VALUES ('$_POST[field_of_employeement]','$_POST[number_of_income]','$_POST[year]','$_POST[branch]','$_POST[id]','$_POST[position]','$_POST[salary]')";
echo $sql;
    $res=$conn->query($sql);
if($res){
$_SESSION['success']="Economic information created Successfully";
$sql="update users set form_done=1 WHERE id=$_POST[id]";
$res=$conn->query($sql);
    header("location:Loan.php");
}
}
if(isset($_POST['update_economic'])){

   $sql="UPDATE `economic` SET `field_of_employeement`='$_POST[field_of_employeement]',`number_of_income`='$_POST[number_of_income]',
   `year`='$_POST[year]',`branch`='$_POST[branch]',`position`='$_POST[position]',`salary`='$_POST[salary]' WHERE user_id=$_POST[id]";
    $res=$conn->query($sql);
if($res){
$_SESSION['success']="Economic information created Successfully";
    header("location:report.php");
}
}
if(isset($_POST['update_personal'])){
    $sql="UPDATE `personal` SET `Number_of_dependents`='$_POST[Number_of_dependents]',`Marriage_Status`='$_POST[Marriage_Status]',`Educational_Status`='$_POST[Educational_Status]',`Criminal_record`='$_POST[Criminal_record]' WHERE user_id=$_POST[id]";

     $res=$conn->query($sql);
 if($res){
 $_SESSION['success']="Personal information created Successfully";
 header("location:economic.php");
 }
 }

?>


