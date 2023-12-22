 
<?php
include "../ratelimiter.php";
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
include "./functions.php";
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
} else if (isset($_POST['add_personal']) || isset($_POST['Number_of_dependents'])) {
    $existingPersonalRecord = checkExistingPersonalRecord($conn, $_SESSION['id']);
    if ($existingPersonalRecord) {
        $errors[] = "Personal Information already created";
        exit();
    }
    $errors = array();
    $response = array();
    $nod = $_POST['Number_of_dependents'];
    if (!is_numeric($nod) || $nod < 0) {
        $errors[] = "Number of Dependents must be a non-negative number.";
    }
    if ($nod === '' || $nod === null) {
        $errors[] = "Number of Dependents is Required";
    }
    if ($nod > 10) {
        $errors[] = "Number of Dependents should be less than Ten";
    }
    $marriage = $_POST['Marriage_Status'];
    if (empty($marriage)) {
        $errors[] = "Marriage Status is required.";
    }
    $allowedMarriageStatus = ["Single", "Divorced", "Married"];
    if (!in_array($marriage, $allowedMarriageStatus)) {
        $errors[] = "Marriage Status must be one of Single, Divorced, Married.";
    }
    $educational = $_POST['Educational_Status'];
    if (empty($educational)) {
        $errors[] = "Educational Status is required.";
    }
    $allowedEducationalStatus = ["Degree", "Diploma", "PHD", "Masters", "Below highSchool"];
    if (!in_array($educational, $allowedEducationalStatus)) {
        $errors[] = "Educational Status must be one of Degree, Diploma, PHD, Masters, Below highSchool.";
    }
    $cr = $_POST['Criminal_record'];
    $allowedCriminalRecord = ["No", "Yes/Past Five Years", "Yes/More Than Five Years"];
    if (empty($cr)) {
        $errors[] = "Criminal Record is required.";
    }

    if (empty($errors)) {
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);
        $stmt = $conn->prepare("INSERT INTO `personal`(`Number_of_dependents`, `Marriage_Status`, `Educational_Status`, `Criminal_record`, `user_id`, `personal_score`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $nod, $marriage, $educational, $cr, $_SESSION['id'], $score);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Personal information created Successfully";
            $response = array('success' => $_SESSION['success']);
            header('Content-Type: application/json');
            echo json_encode($response);
            insertLog($conn, $_SESSION['id'], "Personal information created Successfully for user " . $_SESSION['id']);
            exit();
        } else {
            $_SESSION['error'] = "Error inserting record: " . $stmt->error;
            insertLog($conn, $_SESSION['id'], "Unable to create Personal information for user " . $_SESSION['id']);
        }
        $stmt->close();
    } else {
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
    if ($nod === '' || $nod === null) {
        $errors[] = "Number of Dependents is Required";
    }
    if ($nod > 10) {
        $errors[] = "Number of Dependents should be less than Ten.";
    }
    $marriage = $_POST['marrigeStatus'];
    if (empty($marriage)) {
        $errors[] = "Marriage Status is required.";
    }
    $allowedMarriageStatus = ["Single", "Divorced", "Married"];
    if (!in_array($marriage, $allowedMarriageStatus)) {
        $errors[] = "Marriage Status must be one of Single, Divorced, Married.";
    }
    $educational = $_POST['educationalStatus'];
    if (empty($educational)) {
        $errors[] = "Educational Status is required.";
    }
    $allowedEducationalStatus = ["Degree", "Diploma", "PHD", "Masters", "Below highSchool"];
    if (!in_array($educational, $allowedEducationalStatus)) {
        $errors[] = "Educational Status must be one of Degree, Diploma, PHD, Masters, Below highSchool.";
    }
    $cr = $_POST['criminalRecord'];
    $allowedCriminalRecord = ["No", "Yes/Past Five Years", "Yes/More Than Five Years"];
    if (empty($cr)) {
        $errors[] = "Criminal Record is required.";
    }

    if (empty($errors)) {
        $age = getAge($_SESSION['dob']);
        $score = personalScore($age, $educational, $marriage, $nod, $cr);
        $stmt = $conn->prepare("UPDATE `personal` SET `Number_of_dependents`=?, `Marriage_Status`=?, `Educational_Status`=?, `Criminal_record`=?, `personal_score`=? WHERE user_id=?");
        $stmt->bind_param("isssis", $_POST['numberOfDependents'], $_POST['marrigeStatus'], $_POST['educationalStatus'], $_POST['criminalRecord'], $score, $_SESSION['id']);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Personal information updated Successfully";
            $response = array('success' => $_SESSION['success']);
            header('Content-Type: application/json');
            echo json_encode($response);
            insertLog($conn, $_SESSION['id'], "Personal information Updated Successfully for user " . $_SESSION['id']);
            exit();
        } else {
            $_SESSION['error'] = "Error updating record: " . $stmt->error;
            insertLog($conn, $_SESSION['id'], "Unable to update Personal information for user " . $_SESSION['id']);
        }
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
    if ($number_of_income === '' || $number_of_income === null) {
        $errors[] = "Number of Income is required.";
    }

    // Validate Year of Employment
    $year = $_POST['year'];
    if (empty($year)) {
        $errors[] = "Year of Employment is required.";
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
    $existingEconomicRecord = checkExistingEconomicRecord($conn, $_SESSION['id']);
    if ($existingEconomicRecord) {
        $errors[] = "Economic Information already created";
        exit();
    }

    if (empty($errors)) {
        $Source_of_income = $_POST['number_of_income'];
        $Experience = $_POST['year'];
        $Number_Of_Loans = 0;
        $fully_repaid_loans = 0;
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
        $stmt = $conn->prepare("INSERT INTO `economic`(`field_of_employeement`, `number_of_income`, `year`, `user_id`, `position`, `salary`, `economic_score`) VALUES (?, ?, ?, ?, ?,  ?, ?)");
        $stmt->bind_param("sisssii", $field_of_employment, $number_of_income, $year, $_SESSION['id'], $position, $salary, $score);
        if ($stmt->execute()) {
            $salary = $_POST['salary'];
            $level = getLevel($salary);
            $limit = $LEVEL[$level];
            $stmt = $conn->prepare("UPDATE users SET form_done=1, credit_limit=?, level=? WHERE user_id=?");
            $stmt->bind_param("iss", $limit, $level, $_SESSION['id']);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Economic information created Successfully";
                $response = array('success' => $_SESSION['success']);
                header('Content-Type: application/json');
                echo json_encode($response);
                insertLog($conn, $_SESSION['id'], "Economic information created Successfully for user " . $_SESSION['id']);
                exit();
            }
        }
    } else {
        // There are validation errors, return them as JSON
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
        insertLog($conn, $_SESSION['id'], "Unable to create Economic information for user " . $_SESSION['id']);
        exit();
    }
} else if (isset($_POST['update_economic'])) {
    $errors = array();
    $response = array();
    $field_of_employment = $_POST['field_of_employeement'];
    if (empty($field_of_employment)) {
        $errors[] = "Field of Employment is required.";
    }
    $number_of_income = $_POST['number_of_income'];
    if (!is_numeric($number_of_income) || $number_of_income < 0) {
        $errors[] = "Number of Income must be a non-negative number.";
    }
    if ($number_of_income === '' || $number_of_income === null) {
        $errors[] = "Number of Income is required.";
    }
    $year = $_POST['year'];
    if (empty($year)) {
        $errors[] = "Year of Employment is required.";
    }

    $position = $_POST['position'];
    if (empty($position)) {
        $errors[] = "Position is required.";
    }
    $salary = $_POST['salary'];
    if (!is_numeric($salary) || $salary < 0) {
        $errors[] = "Salary must be a non-negative number.";
    }

    if (empty($errors)) {
        $Source_of_income = $_POST['number_of_income'];
        $Experience = $_POST['year'];
        $Number_Of_Loans = 0;
        $fully_repaid_loans = 0;
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
        $stmt = $conn->prepare("UPDATE `economic` SET `field_of_employeement`=?, `number_of_income`=?, `year`=?, `position`=?, `salary`=?, `economic_score`=? WHERE user_id=?");
        $stmt->bind_param("sissiis", $field_of_employment, $number_of_income, $year, $position, $salary, $score, $_SESSION['id']);
        if ($stmt->execute()) {
            $salary = $_POST['salary'];
            $level = getLevel($salary);
            $limit = $LEVEL[$level];
            $stmt = $conn->prepare("UPDATE users SET form_done=1, credit_limit=?, level=? WHERE user_id=?");
            $stmt->bind_param("iss", $limit, $level, $_SESSION['id']);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Economic information updated Successfully";
                $response = array('success' => $_SESSION['success']);
                header('Content-Type: application/json');
                echo json_encode($response);
                insertLog($conn, $_SESSION['id'], "Economic Information Updated Successfully for user " . $_SESSION['id']);
                exit(); // Add this to prevent further execution
            }
        }
    } else {
        // There are validation errors, return them as JSON
        $response = array('errors' => $errors);
        header('Content-Type: application/json');
        insertLog($conn, $_SESSION['id'], "Unable to Update Economic information for user " . $_SESSION['id']);
        echo json_encode($response);
    }
}
function checkExistingPersonalRecord($conn, $userId)
{
    $stmt = $conn->prepare("SELECT * FROM `personal` WHERE `user_id` = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function checkExistingEconomicRecord($conn, $userId)
{
    $stmt = $conn->prepare("SELECT * FROM `economic` WHERE `user_id` = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

?>


