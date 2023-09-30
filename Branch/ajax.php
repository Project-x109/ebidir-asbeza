<?php 
include '../connect.php';
include '../user/functions.php';
session_start();
function dateDiffInDays($date1, $date2) {
    
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);
  
    return abs(round($diff / 86400));
}
if(isset( $_GET['loan_id'])){
    $id= $_GET['loan_id'];
    $sql="SELECT * from loans where id='$id'";
    $res=$conn->query($sql);
    $row=$res->fetch_assoc();

    $date=date("y-m-d ");
    $date2=date("23-09-30");
$days=dateDiffInDays($date,$row['createdOn']);
$tranactionId=$SixDigitRandomNumber = rand(100000,999999);
$latency="";
if($days<=30)
$latency="green";
else if($days>30 && $days<=40)
$latency="yellow";
else
$latency="red";
$sql="SELECT * from users where id='$row[user_id]'";
$res2=$conn->query($sql);
$row2=$res2->fetch_assoc();

 $sql="INSERT INTO transactions(`transaction_id`,`loan_id`,`user_id`,`loan_amount`,`credit_limit`,`credit_level`,`updatedBy`)
 values('$tranactionId','$id','$row[user_id]','$row[price]','$row2[credit_limit]','$row2[level]','$_SESSION[id]')";
 $res2=$conn->query($sql);
 if($res2){
    $datetime=date("Y-m-d h:i:sa");
    $sql="UPDATE loans set status='paid',closedOn='$datetime' where id='$id'";
    $conn->query($sql);
    $level=$days<40? incrementLevel($row2['level']):decrementLevel($row2['level']);
    $limit=getLimit($level);
    $sql="UPDATE users set level='$level', credit_limit='$limit' where id='$row[user_id]'";
    $conn->query($sql);
    $sql="SELECT * FROM economic where user_id='$row[user_id]'";
    $res3=$conn->query($sql);
    $row3=$res3->fetch_assoc();
    $Number_Of_Loans=$row3['Number_Of_Loans'];
    $fully_repaid_loans=$row3['fully_repaid_loans'];
    $Source_of_income=$row3['number_of_income'];
    $Experience=$row3['year'];
    $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
    $Number_Of_Loans--;
    if($Number_Of_Loans<0)
    $Number_Of_Loans=0;
    $fully_repaid_loans++;
    $sql="update economic set Number_Of_Loans=$Number_Of_Loans, economic_score=$score,fully_repaid_loans=$fully_repaid_loans where user_id='$row[user_id]'";
    $conn->query($sql);
 }
}

?>