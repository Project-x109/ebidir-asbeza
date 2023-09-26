 
<?php
include "../connect.php";
include "./functions.php";
session_start();
if(isset($_POST['add_personal'])){
    $age=getAge($_SESSION['dob']);
    $nod=$_POST['Number_of_dependents'];
    $marriage=$_POST['Marriage_Status'];
    $educational=$_POST['Educational_Status'];
    $cr=$_POST['Criminal_record'];
    $score=personalScore($age,$educational,$marriage,$nod,$cr);
    $sql="INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`,`user_id`,`personal_score`) 
    VALUES ('$nod','$marriage','$educational','$cr','$_POST[id]','$score')";
echo $sql;
    $res=$conn->query($sql);
if($res){

$_SESSION['success']="Personal information created Successfully";
    header("location:economic.php");
}
}
if(isset($_POST['add_economic'])){
    $Source_of_income=$_POST['number_of_income'];
    $Experience=$_POST['year'];
    $Number_Of_Loans=0;
    $fully_repaid_loans=0;
    $score= EconomicScore($Source_of_income,$Experience,$Number_Of_Loans,$fully_repaid_loans);
    $sql="INSERT INTO `economic`(`field_of_employeement`, `number_of_income`, `year`, `branch`,`user_id`,`position`,`salary`,`economic_score`) 
    VALUES ('$_POST[field_of_employeement]','$_POST[number_of_income]','$_POST[year]','$_POST[branch]','$_POST[id]','$_POST[position]','$_POST[salary]',$score)";
echo $sql;
    $res=$conn->query($sql);
if($res){
$_SESSION['success']="Economic information created Successfully";
$salary=$_POST['salary'];
$level=getLevel($salary);
$limit=$LEVEL[$level];
$sql="update users set form_done=1,credit_limit=$limit,level='$level' WHERE id=$_POST[id]";
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
$salary=$_POST['salary'];
$level=getLevel($salary);
$limit=$LEVEL[$level];
$sql="update users set form_done=1,credit_limit=$limit,level='$level' WHERE id=$_POST[id]";
$res=$conn->query($sql);
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
 if(isset($_POST['checkout'])){

$sql="SELECT * FROM users where id=$_POST[id]";
$res=$conn->query($sql);
$row=$res->fetch_assoc();
$date=date('Y-m-d h:i:s');
$sql="INSERT INTO loans(`user_id`,`price`,`createdOn`)Values($_POST[id],$_POST[price],'$date')";
$res=$conn->query($sql);
if($res){
$limit=$row['credit_limit']-$_POST['price'];
$sql="update users set credit_limit=$limit WHERE id=$_POST[id]";
$res=$conn->query($sql);
header("location:report.php");
}
 }
?>


