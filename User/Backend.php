 
<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
include "./functions.php";
if (isset($_POST['add_personal']) || isset($_POST['Number_of_dependents'])) {
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
}
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

        // Calculate the personal score (modify this logic as needed)
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);

        // Prepare and bind the SQL insert statement
        $stmt = $conn->prepare("INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`, `user_id`, `personal_score`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $nod, $marriage, $educational, $cr, $_POST['id'], $score);

        // Execute the SQL insert statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Personal information created Successfully";

            // Set the success message in the response
            $response = array('success' => $_SESSION['success']);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit(); // Add this to prevent further execution
        } else {
            $_SESSION['error'] = "Error inserting record: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // There are validation errors, send them back to the frontend
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else if (isset($_POST['update_personal'])) {
    $response = array();
    $errors = array();

    // Validate Number of Dependents
    $nod = $_POST['numberOfDependents'];
    if (!is_numeric($nod) || $nod < 0) {
        $errors[] = "Number of Dependents must be a non-negative number.";
    }
    if (empty($nod)) {
        $errors[] = "Number of Dependents is required.";
    }
    if ($nod > 10) {
        $errors[] = "Number of Dependents should be less than Ten.";
    }

    // Validate Marriage Status
    $marriage = $_POST['marrigeStatus'];
    if (empty($marriage)) {
        $errors[] = "Marriage Status is required.";
    }
    $allowedMarriageStatus = ["Single", "Divorced", "Married"];
    if (!in_array($marriage, $allowedMarriageStatus)) {
        $errors[] = "Marriage Status must be one of Single, Divorced, Married.";
    }

    // Validate Educational Status
    $educational = $_POST['educationalStatus'];
    if (empty($educational)) {
        $errors[] = "Educational Status is required.";
    }
    $allowedEducationalStatus = ["Degree", "Diploma", "PHD", "Masters", "Below highSchool"];
    if (!in_array($educational, $allowedEducationalStatus)) {
        $errors[] = "Educational Status must be one of Degree, Diploma, PHD, Masters, Below highSchool.";
    }

    // Validate Criminal Record
    $cr = $_POST['criminalRecord'];
    $allowedCriminalRecord = ["No", "Yes/Past Five Years", "Yes/More Than Five Years"];
    if (empty($cr)) {
        $errors[] = "Criminal Record is required.";
    }

    if (empty($errors)) {
        // All form fields are valid, proceed with database update
        // Calculate the personal score (modify this logic as needed)
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);

        // Prepare and bind the SQL update statement
        $stmt = $conn->prepare("UPDATE `personal` SET `Number_of_dependents`=?, `Marriage_Status`=?, `Educational_Status`=?, `Criminal_record`=?, `personal_score`=? WHERE user_id=?");
        $stmt->bind_param("isssis", $_POST['numberOfDependents'], $_POST['marrigeStatus'], $_POST['educationalStatus'], $_POST['criminalRecord'], $score, $_POST['id']);

        // Execute the SQL update statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Personal information updated Successfully";

            // Set the success message in the response
            $response = array('success' => $_SESSION['success']);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit(); // Add this to prevent further execution
        } else {
            $_SESSION['error'] = "Error updating record: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // There are validation errors, send them back to the frontend
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else if (isset($_POST['add_economic'])) {
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

        // Prepare and bind the SQL insert statement
        $stmt = $conn->prepare("INSERT INTO `economic`(`field_of_employeement`, `number_of_income`, `year`, `branch`, `user_id`, `position`, `salary`, `economic_score`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssii", $field_of_employment, $number_of_income, $year, $branch, $_POST['id'], $position, $salary, $score);

        // Execute the SQL insert statement
        if ($stmt->execute()) {
            $salary = $_POST['salary'];
            $level = getLevel($salary);
            $limit = $LEVEL[$level];

            // Prepare and bind the SQL update statement for users table
            $stmt = $conn->prepare("UPDATE users SET form_done=1, credit_limit=?, level=? WHERE user_id=?");
            $stmt->bind_param("iss", $limit, $level, $_POST['id']);

            if ($stmt->execute()) {
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
        exit();
    }
} else if (isset($_POST['update_economic'])) {
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
        // All form fields are valid, proceed with database update
        $Source_of_income = $_POST['number_of_income'];
        $Experience = $_POST['year'];
        $Number_Of_Loans = 0;
        $fully_repaid_loans = 0;
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);

        // Prepare and bind the SQL update statement
        $stmt = $conn->prepare("UPDATE `economic` SET `field_of_employeement`=?, `number_of_income`=?, `year`=?, `branch`=?, `position`=?, `salary`=?, `economic_score`=? WHERE user_id=?");
        $stmt->bind_param("sisssiis", $field_of_employment, $number_of_income, $year, $branch, $position, $salary, $score, $_POST['id']);

        // Execute the SQL update statement
        if ($stmt->execute()) {
            $salary = $_POST['salary'];
            $level = getLevel($salary);
            $limit = $LEVEL[$level];

            // Prepare and bind the SQL update statement for users table
            $stmt = $conn->prepare("UPDATE users SET form_done=1, credit_limit=?, level=? WHERE user_id=?");
            $stmt->bind_param("iss", $limit, $level, $_POST['id']);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Economic information updated Successfully";
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
$token = htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8');
if (!$token || $token !== $_SESSION['token']) {
    $_SESSION['error'] = "Authorization Error";
    header("Location: index.php");
    exit;
} else if (isset($_POST['checkout'])) {

    $sql = "SELECT * FROM users where user_id='$_POST[id]'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $date = date('Y-m-d h:i:s');
    $sql = "INSERT INTO loans(`user_id`,`price`,`createdOn`)Values('$_POST[id]',$_POST[price],'$date')";
    $res = $conn->query($sql);
    if ($res) {
        $_SESSION['user_id']=$conn->insert_id;
        $limit = $row['credit_limit'] - $_POST['price'];
        $sql = "update users set credit_limit=$limit WHERE user_id='$_POST[id]'";
        $res = $conn->query($sql);
        header("location:../branch/paymentDone.php");
    }
}
?>


