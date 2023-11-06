<?php
include '../connect.php';
include '../user/functions.php';
include "../common/Authorization.php";
$requiredRoles = array('branch', 'delivery', 'Admin'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
session_start();
function dateDiffInDays($date1, $date2)
{

    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);

    return abs(round($diff / 86400));
}
/* if (isset($_GET['loan_id'])) {
    $id = $_GET['loan_id'];
    $sql = "SELECT * from loans where id='$id'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $date = date("y-m-d");
    $date2 = date("23-09-30");
    $days = dateDiffInDays($date, $row['createdOn']);
    $tranactionId = $SixDigitRandomNumber = rand(100000, 999999);
    $latency = "";
    if ($days <= 30)
        $latency = "green";
    else if ($days > 30 && $days <= 40)
        $latency = "yellow";
    else
        $latency = "red";
    $sql = "SELECT * from users where user_id='$row[user_id]'";
    $res2 = $conn->query($sql);
    $row2 = $res2->fetch_assoc();
    $sql = "INSERT INTO transactions(`transaction_id`,`loan_id`,`user_id`,`loan_amount`,`credit_limit`,`credit_level`,`updatedBy`)
 values('$tranactionId','$id','$row[user_id]','$row[price]','$row2[credit_limit]','$row2[level]','$_SESSION[id]')";
    insertLog($conn, $_SESSION['id'], "a new Transaction for loan id of " . $id . " and  User id " . "$row[user_id]" . " Has been created ");
    $res2 = $conn->query($sql);
    if ($res2) {
        $datetime = date("Y-m-d h:i:sa");
        $sql = "UPDATE loans set status='paid',closedOn='$datetime' where id='$id'";
        $conn->query($sql);

        insertLog($conn, $_SESSION['id'], "Loan for loan id of " . $id . " and  User id " . "$row[user_id]" . " Has been updated to paid");
        $level = $days < 40 ? incrementLevel($row2['level']) : decrementLevel($row2['level']);
        $limit = getLimit($level);
        $sql = "UPDATE users set level='$level', credit_limit='$limit' where user_id='$row[user_id]'";
        $conn->query($sql);
        insertLog($conn, $_SESSION['id'], "the Level and credit Limit for " . " User id " . "$row[user_id]" . " Has been updated to a level of " . $level . " and a limit of " . $limit);
        $sql = "SELECT * FROM economic where user_id='$row[user_id]'";
        $res3 = $conn->query($sql);
        $row3 = $res3->fetch_assoc();
        $Number_Of_Loans = $row3['Number_Of_Loans'];
        $fully_repaid_loans = $row3['fully_repaid_loans'];
        $Source_of_income = $row3['number_of_income'];
        $Experience = $row3['year'];
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
        $Number_Of_Loans--;
        if ($Number_Of_Loans < 0)
            $Number_Of_Loans = 0;
        $fully_repaid_loans++;
        $sql = "update economic set Number_Of_Loans=$Number_Of_Loans, economic_score=$score,fully_repaid_loans=$fully_repaid_loans where user_id='$row[user_id]'";
        $res = $conn->query($sql);
        if ($res)
            echo 1;
        else
            echo 0;
    }
} */

if (isset($_GET['loan_id'])) {
    $id = $_GET['loan_id'];
    $sql = "SELECT * FROM loans WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $date = date("y-m-d");
    $date2 = date("23-09-30");
    $days = dateDiffInDays($date, $row['createdOn']);
    $tranactionId = $SixDigitRandomNumber = rand(100000, 999999);
    $latency = "";
    if ($days <= 30)
        $latency = "green";
    else if ($days > 30 && $days <= 40)
        $latency = "yellow";
    else
        $latency = "red";
    $sql = "SELECT * FROM users WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $row['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row2 = $result->fetch_assoc();
    $stmt->close();
    $sql = "INSERT INTO transactions (transaction_id, loan_id, user_id, loan_amount, credit_limit, credit_level, updatedBy) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssddss", $tranactionId, $id, $row['user_id'], $row['price'], $row2['credit_limit'], $row2['level'], $_SESSION['id']);
    $stmt->execute();
    $stmt->close();
    insertLog($conn, $_SESSION['id'], "a new Transaction for loan id of $id and User id {$row['user_id']} Has been created");
    $datetime = date("Y-m-d h:i:sa");
    $sql = "UPDATE loans SET status='paid', closedOn=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $datetime, $id);
    $stmt->execute();
    $stmt->close();
    insertLog($conn, $_SESSION['id'], "Loan with loan id of $id and User id {$row['user_id']} Has been updated to paid");
    $level = $days < 40 ? incrementLevel($row2['level']) : decrementLevel($row2['level']);
    $limit = getLimit($level);
    $sql = "UPDATE users SET level=?, credit_limit=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $level, $limit, $row['user_id']);
    $stmt->execute();
    $stmt->close();
    insertLog($conn, $_SESSION['id'], "the Level and credit Limit for User id {$row['user_id']} Has been updated from {$row2['level']} to a level of $level  and from {$row2['credit_limit']} to a limit of $limit");
    $sql = "SELECT * FROM economic WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $row['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row3 = $result->fetch_assoc();
    $stmt->close();
    $Number_Of_Loans = $row3['Number_Of_Loans'];
    $fully_repaid_loans = $row3['fully_repaid_loans'];
    $Source_of_income = $row3['number_of_income'];
    $Experience = $row3['year'];
    $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
    $Number_Of_Loans--;
    if ($Number_Of_Loans < 0)
        $Number_Of_Loans = 0;
    $fully_repaid_loans++;
    $sql = "UPDATE economic SET Number_Of_Loans=?, economic_score=?, fully_repaid_loans=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idis", $Number_Of_Loans, $score, $fully_repaid_loans, $row['user_id']);
    insertLog($conn, $_SESSION['id'], "The Economic status of User with User id {$row['user_id']} Has been updated");
    $stmt->execute();

    if ($stmt->affected_rows > 0)
        echo 1;
    else
        echo 0;
}


if (isset($_GET['loan_id_online'])) {
    $id = $_GET['loan_id_online'];

    // Prepare and execute the query to retrieve loan information
    $sql = "SELECT * FROM loans WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Prepare and execute the query to retrieve user information
    $sql = "SELECT * FROM users WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $row['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row2 = $result->fetch_assoc();
    $stmt->close();

    if ($result) {
        // Prepare and execute the query to update loan status to 'approved'
        $sql = "UPDATE loans SET status='approved' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        insertLog($conn, $_SESSION['id'], "Loan with loan id of $id and User id {$row['user_id']} Has been updated to approved");

        // Prepare and execute the query to retrieve economic information
        $sql = "SELECT * FROM economic WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $row['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row3 = $result->fetch_assoc();
        $stmt->close();

        $Number_Of_Loans = $row3['Number_Of_Loans'];
        $fully_repaid_loans = $row3['fully_repaid_loans'];
        $Source_of_income = $row3['number_of_income'];
        $Experience = $row3['year'];
        $score = EconomicScore($Source_of_income, $Experience, $Number_Of_Loans, $fully_repaid_loans);
        $Number_Of_Loans++;

        // Prepare and execute the query to update economic information
        $sql = "UPDATE economic SET Number_Of_Loans=?, economic_score=?, fully_repaid_loans=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idis", $Number_Of_Loans, $score, $fully_repaid_loans, $row['user_id']);
        insertLog($conn, $_SESSION['id'], "The Economic status of User with User id {$row['user_id']} Has been updated");
        $stmt->execute();
        $stmt->close();

        if ($stmt->affected_rows > 0)
            echo 1;
        else
            echo 0;
    }
}
