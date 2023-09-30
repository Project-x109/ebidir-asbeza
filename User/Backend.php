 
<?php
include "../connect.php";
include "./functions.php";
session_start();
/* if (isset($_POST['add_personal'])||isset($_POST['Number_of_dependents'])) {
    // Form validation
    $errors = array();
    // Validate Number of Dependents
    $nod = $_POST['Number_of_dependents'];
    if (!is_numeric($nod) || $nod < 0) {
        $errors[] = "Number of Dependents must be a non-negative number.";
    }
    if (empty($nod)) {
        $errors[] = "Number of Dependents is Required";
    }
    if ($nod > 10) {
        $errors[] = "Number of Dependents should be less than Ten";
    }
    // Validate Marriage Status
    $marriage = $_POST['Marriage_Status'];
    if (empty($marriage)) {
        $errors[] = "Marriage Status is required.";
    }
    $allowedMarriageStatus = ["Single", "Divorced", "Married"];
    // Validate Educational Status
    $educational = $_POST['Educational_Status'];
    if (empty($educational)) {
        $errors[] = "Educational Status is required.";
    }
    if (!in_array($marriage, $allowedMarriageStatus)) {
        $errors[] = "Marriage Status must be one of: Single, Divorced, Married.";
    }
    // Validate Educational Status
    $educational = $_POST['Educational_Status'];
    $allowedEducationalStatus = ["Degree", "Diploma", "PHD", "Masters", "Below highSchool"];
    if (!in_array($educational, $allowedEducationalStatus)) {
        $errors[] = "Educational Status must be one of: Degree, Diploma, PHD, Masters, Below highSchool.";
    }
    // Validate Criminal Record
    $cr = $_POST['Criminal_record'];
    $allowedCriminalRecord = ["No", "Yes/Past Five Years", "Yes/More Than Five Years"];
    if (empty($cr)) {
        $errors[] = "Criminal Record is required.";
    }
    echo "Form validation completed.<br>";
    // Check if there are any validation errors
    if (empty($errors)) {
        echo "No validation errors.<br>";
        // All form fields are valid, proceed with database insertion
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);
        $sql = "INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`,`user_id`,`personal_score`) 
                VALUES ('$nod','$marriage','$educational','$cr','$_POST[id]','$score')";
        // Attempt to execute the SQL query
        echo $sql;
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Personal information created Successfully";
            header("location: economic.php");
            exit(); // Add this to prevent further execution
        } else {
            header("location: personal.php");
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // There are validation errors, redirect back to the form page and display errors
        $_SESSION['errors'] = $errors;
        header("location: personal.php");
    }
} */


if (isset($_POST['add_personal']) || isset($_POST['Number_of_dependents'])) {
    // Form validation
    $errors = array();
    $response = array();
    $nod = $_POST['Number_of_dependents'];
    if (!is_numeric($nod) || $nod < 0) {
        $errors[] = "Number of Dependents must be a non-negative number.";
    }
    if (empty($nod)) {
        $errors[] = "Number of Dependents is Required";
    }
    if ($nod > 10) {
        $errors[] = "Number of Dependents should be less than Ten";
    }

    // Validate Marriage Status
    $marriage = $_POST['Marriage_Status'];
    if (empty($marriage)) {
        $errors[] = "Marriage Status is required.";
    }
    $allowedMarriageStatus = ["Single", "Divorced", "Married"];
    if (!in_array($marriage, $allowedMarriageStatus)) {
        $errors[] = "Marriage Status must be one of Single, Divorced, Married.";
    }

    // Validate Educational Status
    $educational = $_POST['Educational_Status'];
    if (empty($educational)) {
        $errors[] = "Educational Status is required.";
    }
    $allowedEducationalStatus = ["Degree", "Diploma", "PHD", "Masters", "Below highSchool"];
    if (!in_array($educational, $allowedEducationalStatus)) {
        $errors[] = "Educational Status must be one of Degree, Diploma, PHD, Masters, Below highSchool.";
    }

    // Validate Criminal Record
    $cr = $_POST['Criminal_record'];
    $allowedCriminalRecord = ["No", "Yes/Past Five Years", "Yes/More Than Five Years"];
    if (empty($cr)) {
        $errors[] = "Criminal Record is required.";
    }
    // Check if there are any validation errors
    if (empty($errors)) {
        // All form fields are valid, proceed with database insertion

        // All form fields are valid, proceed with database insertion
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);
        $sql = "INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`,`user_id`,`personal_score`) 
                VALUES ('$nod','$marriage','$educational','$cr','$_POST[id]','$score')";
        // Attempt to execute the SQL query

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Personal information created Successfully";

            // Set the success message in the response
            $response = array('success' => $_SESSION['success']);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit(); // Add this to prevent further execution
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // There are validation errors, send them back to the frontend
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} 


// Add this line before validation
if (isset($_POST['add_economic'])) {
    // Form validation
    $errors = array();
    $response = array();
    // Validate Field of Employment
    $field_of_employment = $_POST['field_of_employeement'];
    if (empty($field_of_employment)) {
        $errors[] = "Field of Employment is required.";
    }

    // Validate Number of Income
    $number_of_income = $_POST['number_of_income'];
    if (!is_numeric($number_of_income) || $number_of_income < 0) {
        $errors[] = "Number of Income must be a non-negative number.";
    }
    if (empty($number_of_income)) {
        $errors[] = "Number of Income is required.";
    }

    // Validate Year of Employment
    $year = $_POST['year'];
    if (empty($year)) {
        $errors[] = "Year of Employment is required.";
    }

    // Validate Branch Name
    $branch = $_POST['branch'];
    if (empty($branch)) {
        $errors[] = "Branch Name is required.";
    }

    // Validate Position
    $position = $_POST['position'];
    if (empty($position)) {
        $errors[] = "Position is required.";
    }

    // Validate Salary
    $salary = $_POST['salary'];
    if (!is_numeric($salary) || $salary < 0) {
        $errors[] = "Salary must be a non-negative number.";
    }

    if (empty($errors)) {
        // All form fields are valid, proceed with database insertion
        $Source_of_income = $_POST['number_of_income'];
        $Experience = $_POST['year'];
        $Number_Of_Loans = 0;
        $fully_repaid_loans = 0;
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
        $sql = "INSERT INTO `economic`(`field_of_employeement`, `number_of_income`, `year`, `branch`,`user_id`,`position`,`salary`,`economic_score`) 
                VALUES ('$_POST[field_of_employeement]','$_POST[number_of_income]','$_POST[year]','$_POST[branch]','$_POST[id]','$_POST[position]','$_POST[salary]',$score)";

        // Attempt to execute the SQL query
        if ($conn->query($sql) === TRUE) {
            $salary = $_POST['salary'];
            $level = getLevel($salary);
            $limit = $LEVEL[$level];
            $sql = "UPDATE users SET form_done=1, credit_limit=$limit, level='$level' WHERE id=$_POST[id]";
            $res = $conn->query($sql);
            if ($res) {
                $_SESSION['success'] = "Economic information created Successfully";
                $response = array('success' => $_SESSION['success']);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit(); // Add this to prevent further execution
            }
        }
    } else {
        // There are validation errors, return them as JSON
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

if (isset($_POST['update_economic'])) {

    $sql = "UPDATE `economic` SET `field_of_employeement`='$_POST[field_of_employeement]',`number_of_income`='$_POST[number_of_income]',
   `year`='$_POST[year]',`branch`='$_POST[branch]',`position`='$_POST[position]',`salary`='$_POST[salary]' WHERE user_id=$_POST[id]";
    $res = $conn->query($sql);
    if ($res) {
        $_SESSION['success'] = "Economic information created Successfully";
        $salary = $_POST['salary'];
        $level = getLevel($salary);
        $limit = $LEVEL[$level];
        $sql = "update users set form_done=1,credit_limit=$limit,level='$level' WHERE id=$_POST[id]";
        $res = $conn->query($sql);
        header("location:Profileeconomic.php");
    }
}
if (isset($_POST['update_personal'])) {
    $sql = "UPDATE `personal` SET `Number_of_dependents`='$_POST[Number_of_dependents]',`Marriage_Status`='$_POST[Marriage_Status]',`Educational_Status`='$_POST[Educational_Status]',`Criminal_record`='$_POST[Criminal_record]' WHERE user_id=$_POST[id]";

    $res = $conn->query($sql);
    if ($res) {
        $_SESSION['success'] = "Personal information created Successfully";
        header("location:Profilepersonal.php");
    }
}
if (isset($_POST['checkout'])) {

    $sql = "SELECT * FROM users where id=$_POST[id]";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $date = date('Y-m-d h:i:s');
    $sql = "INSERT INTO loans(`user_id`,`price`,`createdOn`)Values($_POST[id],$_POST[price],'$date')";
    $res = $conn->query($sql);
    if ($res) {
        $limit = $row['credit_limit'] - $_POST['price'];
        $sql = "update users set credit_limit=$limit WHERE id=$_POST[id]";
        $res = $conn->query($sql);
        header("location:report.php");
    }
}
?>


