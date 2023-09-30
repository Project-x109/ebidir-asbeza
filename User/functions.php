<?php
$LEVEL = array(
  "LEVEL00" => 0,
  "LEVEL01" => 100,
  "LEVEL02" => 500,
  "LEVEL03" => 1000,
  "LEVEL04" => 1500,
  "LEVEL05" => 2000,
  "LEVEL06" => 2500,
  "LEVEL07" => 3000,
  "LEVEL08" => 3500,
  "LEVEL09" => 4000,
  "LEVEL10" => 4500,
  "LEVEL11" => 5000,
  "LEVEL12" => 5500,
  "LEVEL13" => 6000,
  "LEVEL14" => 6500,
  "LEVEL15" => 7000,
  "LEVEL16" => 8000,
  "LEVEL17" => 9000,
  "LEVEL18" => 10000
);
$levels = [
  "LEVEL00", "LEVEL01", "LEVEL02", "LEVEL03", "LEVEL04", "LEVEL05", "LEVEL06", "LEVEL07", "LEVEL08", "LEVEL09", "LEVEL10", "LEVEL11",
  "LEVEL12", "LEVEL13", "LEVEL14", "LEVEL15", "LEVEL16", "LEVEL17", "LEVEL18"
];
function getLevel($salary)
{
  if ($salary <= 0) {
    return "LEVEL00";
  } elseif ($salary > 0 && $salary <= 1000) {
    return "LEVEL01";
  } elseif ($salary <= 5000) {
    return "LEVEL02";
  } elseif ($salary <= 10000) {
    return "LEVEL03";
  } elseif ($salary <= 15000) {
    return "LEVEL04";
  } elseif ($salary <= 20000) {
    return "LEVEL05";
  } elseif ($salary <= 25000) {
    return "LEVEL06";
  } elseif ($salary <= 30000) {
    return "LEVEL07";
  } elseif ($salary <= 35000) {
    return "LEVEL08";
  } elseif ($salary <= 40000) {
    return "LEVEL09";
  } elseif ($salary <= 45000) {
    return "LEVEL10";
  } elseif ($salary <= 50000) {
    return "LEVEL11";
  } elseif ($salary <= 55000) {
    return "LEVEL12";
  } elseif ($salary <= 60000) {
    return "LEVEL13";
  } elseif ($salary <= 65000) {
    return "LEVEL14";
  } elseif ($salary <= 70000) {
    return "LEVEL15";
  } elseif ($salary <= 80000) {
    return "LEVEL16";
  } elseif ($salary <= 90000) {
    return "LEVEL17";
  } else {
    return "LEVEL18";
  }
}
function incrementLevel($level)
{
  global $levels;
  $index = array_search($level, $levels) + 1;
  if($index>18)
  $index=18;
  $result = $levels[$index];
  return $result;
}
function decrementLevel($level)
{
  global $levels;
  $index = array_search($level, $levels) - 1;
  if($index<0)
  $index=0;
  $result = $levels[$index];
  return $result;
}
function getLimit($level){
  global $LEVEL;
  return $LEVEL[$level];
}
function getAge($dob)
{
  $dob = new DateTime('1993-07-01');
  $today   = new DateTime('today');
  $year = $dob->diff($today)->y;
  return $year;
}
function personalScore($age, $Education_Status, $Marriage_Status, $Number_Of_Dependents, $Criminal_Record)
{
  $score = 0;
  // for age
  if ($age < 18)
    $score = $score + 0;
  else if ($age >= 18 && $age < 24)
    $score = $score + 6;
  else if ($age >= 24 && $age < 38)
    $score = $score + 8;
  else $score = $score + 10;
  // for educational status
  if ($Education_Status == "Below Highschool") $score = $score + 5;
  if ($Education_Status == "Highschool") $score = $score + 6;
  if ($Education_Status == "Diploma") $score = $score + 7;
  if ($Education_Status == "Degree") $score = $score + 8;
  if ($Education_Status == "Masters") $score = $score + 9;
  if ($Education_Status == "Phd")
    $score = $score + 10;
  // mariage status
  if ($Marriage_Status === "Married") $score = $score + 25;
  else $score = $score + 20;
  // number of dependents
  if ($Number_Of_Dependents == 0) $score = $score + 25;
  else if ($Number_Of_Dependents >= 1 && $Number_Of_Dependents < 3)
    $score = $score + 23;
  else if ($Number_Of_Dependents >= 3 && $Number_Of_Dependents < 5)
    $score = $score + 20;
  else if ($Number_Of_Dependents >= 5 && $Number_Of_Dependents < 10)
    $score = $score + 15;
  else if ($Number_Of_Dependents >= 10) $score = $score + 10;
  // Criminal Records
  if ($Criminal_Record == "No") $score = $score + 30;
  else if ($Criminal_Record == "YES/PAST FIVE YEARS") $score = $score + 10;
  else if ($Criminal_Record == "YES/MORE THAN FIVE YEARS") $score = $score + 15;
  return 2 * $score;
};
//   function EconomicScore($Source_of_income,$Experience,$Number_Of_Loans,$fully_repaid_loans){
//     $score = 0;
//     if ($Source_of_income == 0) $score = $score + 0;
//     else if ($Source_of_income == 1) $score = $score + 2;
//     else if ($Source_of_income == 2) $score = $score + 3;
//     else if ($Source_of_income == 3) $score = $score + 4;
//     else if ($Source_of_income == 4) $score = $score + 5;
//     else if ($Source_of_income == 5) $score = $score + 6;
//     else $score = $score + 7.5;

//     // number of loans
//     if ($Number_Of_Loans == 0) $score = $score + 15;
//     else if ($Number_Of_Loans == 1) $score = $score + 10;
//     else if ($Number_Of_Loans == 2) $score = $score + 5;
//     else if ($Number_Of_Loans >= 3) $score = $score + 3;

//     // expriance
//     if ($Experience == 0) $score = $score + 3;
//     else if ($Experience ===1) $score = $score + 3;
//     else if ($Experience >= 2 && $Experience < 10) $score = $score + 5;
//     else if ($Experience >= 10) $score = $score + 7.5;
//     // fully repaid loans
//     if ($fully_repaid_loans == 0) $score = $score + 15;
//     else if ($fully_repaid_loans >= 1 && $fully_repaid_loans < 5)
//       $score = $score + 25;
//     else if ($fully_repaid_loans >= 5 && $fully_repaid_loans <10)
//       $score = $score + 27;
//     else if ($fully_repaid_loans >= 10) $score = $score + 30;
//     //DTI
// $DTI=90;
//     return $score;
//   };
function EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans)
{
  $score = 0;
  if ($Source_of_income == 0) $score = $score + 0;
  else if ($Source_of_income == 1) $score = $score + 2;
  else if ($Source_of_income == 2) $score = $score + 3;
  else if ($Source_of_income == 3) $score = $score + 4;
  else if ($Source_of_income == 4) $score = $score + 5;
  else if ($Source_of_income == 5) $score = $score + 6;
  else $score = $score + 7.5;

  // number of loans
  if ($Number_Of_Loans == 0) $score = $score + 15;
  else if ($Number_Of_Loans == 1) $score = $score + 10;
  else if ($Number_Of_Loans == 2) $score = $score + 5;
  else if ($Number_Of_Loans >= 3) $score = $score + 3;

  // expriance
  if ($Experience == 0) $score = $score + 3;
  else if ($Experience === 1) $score = $score + 3;
  else if ($Experience >= 2 && $Experience < 10) $score = $score + 5;
  else if ($Experience >= 10) $score = $score + 7.5;
  // fully repaid loans
  if ($fully_repaid_loans == 0) $score = $score + 15;
  else if ($fully_repaid_loans >= 1 && $fully_repaid_loans < 5)
    $score = $score + 25;
  else if ($fully_repaid_loans >= 5 && $fully_repaid_loans < 10)
    $score = $score + 27;
  else if ($fully_repaid_loans >= 10) $score = $score + 30;
  //DTI
  $DTI = 90;
  $score = $score + $DTI;
  return 2 * $score;
};
