<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
include '../user/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';
require '../assets/PHPMailer/Exception.php';
/* if (isset($_POST['user'])) {
    $user_id = $_POST['user'];
    $sql = "SELECT * FROM users where user_id='$user_id'";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        header('location:userinfo.php');
    } else {
        // send error message here 
    }
} */

if (isset($_POST['user'])) {
    $user_id = $_POST['user'];

    // Validate user_id (should be exactly 6 digits)
    if (strlen($user_id) !== 6) {
        $response = array('error' => 'Invalid user ID. It should be 6 digits long.');
    } else if (!preg_match('/^EB\d{4}$/', $user_id)) {
        $response = array('error' => 'Invalid user ID format. It should start with "EB" followed by four digits.');
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id'];
            $response = array('success' => 'User found. Redirecting to User Information');
        } else {
            $response = array('error' => 'User ID does not exist in the database.');
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



/* 
if (isset($_POST['branch_checkout'])) {
    $user_id = $_POST['user_id'];
    $sql = "SELECT * FROM users where user_id='$user_id'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $date = date('Y-m-d h:i:s');
    $sql = "SELECT (personal.personal_score+economic.economic_score) as score from personal INNER JOIN economic on personal.user_id=economic.user_id WHERE personal.user_id='$_POST[user_id]'";
    $res = $conn->query($sql);
    $row1 = $res->fetch_assoc();
    $score = $row1['score'];
    $sql2 = "INSERT INTO loans(`user_id`,`price`,`credit_score`,`createdOn`,`provider`) Values('$_POST[user_id]',$_POST[total_price],'$score','$date','$_SESSION[id]')";
    $res = $conn->query($sql2);
    if ($res) {
        $last_id = mysqli_insert_id($conn);
        $limit = $row['credit_limit'] - $_POST['total_price'];
        $sql = "update users set credit_limit=$limit WHERE user_id='$_POST[user_id]'";
        $res = $conn->query($sql);
        //// send success message here 

        $_SESSION['user_id'] = $last_id;
        echo $sql;
        header("location:paymentdone.php");
    }
}

*/


if (isset($_POST['branch_checkout'])) {
    $user_id = $_POST['user_id'];
    $total_price = $_POST['total_price'];

    // Check if the total_price exceeds the user's credit limit
    $sql = "SELECT credit_limit FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $credit_limit = $row['credit_limit'];

        if ($total_price > $credit_limit) {
            // Total price exceeds the credit limit, send an error response
            $response = array('error' => 'Total price exceeds your credit limit.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        // User not found, send an error response
        $response = array('error' => 'User not found.');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $sql = "SELECT (personal.personal_score+economic.economic_score) as score from personal INNER JOIN economic on personal.user_id=economic.user_id WHERE personal.user_id='$_POST[user_id]'";
    $res = $conn->query($sql);
    $row1 = $res->fetch_assoc();
    $score = $row1['score'];
    // Continue with the rest of your code to insert the loan and update the credit limit
    $date = date('Y-m-d h:i:s');
    $sql2 = "INSERT INTO loans(`user_id`,`price`,`credit_score`,`createdOn`,`provider`) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sdsds", $user_id, $total_price, $score, $date, $_SESSION['id']);

    if ($stmt2->execute()) {
        $last_id = $stmt2->insert_id;
        $limit = $credit_limit - $total_price;
        $sql3 = "UPDATE users SET credit_limit = ? WHERE user_id = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("ds", $limit, $user_id);

        if ($stmt3->execute()) {
            $_SESSION['user_id'] = $last_id;

            // Fetch user's email and name from the database using user_id
            $sql4 = "SELECT email, name FROM users WHERE user_id = ?";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param("s", $user_id);
            $stmt4->execute();
            $result4 = $stmt4->get_result();

            if ($result4 && $result4->num_rows > 0) {
                $row4 = $result4->fetch_assoc();
                $recipientEmail = $row4['email'];
                $recipientName = $row4['name'];

                // Send success message as a JSON response
                $response = array('success' => 'Transaction completed successfully.');
                header('Content-Type: application/json');
                echo json_encode($response);

                // Send an email to the user
                sendPasswordEmail($recipientEmail, $recipientName, $conn);

                exit();
            }
        } else {
            // Error updating the credit limit, send an error response
            $response = array('error' => 'Error updating credit limit.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        // Error inserting the loan, send an error response
        $response = array('error' => 'Error inserting loan.');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}


function sendPasswordEmail($recipientEmail, $recipientName, $conn)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'amanuelgirma108@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'xbpnzmxccxgpvnly'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Port may vary depending on the service
        $recipientQuery = "SELECT  name FROM users WHERE email = '$recipientEmail'";
        $recipientResult = $conn->query($recipientQuery);
        if ($recipientResult->num_rows > 0) {
            $row = $recipientResult->fetch_assoc();
            $recipientName = $row['name'];
            // Sender info
            $mail->setFrom('amanuelgirma108@gmail.com', 'E-Bidir'); // Replace with your name and email
            $mail->addAddress($recipientEmail, $recipientName); // Add recipient from the database
        } else {
            // If the token doesn't match any user, handle the error
            throw new Exception("Recipient not found in the database.");
        }
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Password';
        $loginlink = "http://localhost/sneat-bootstrap-html-admin-template-free/index.php";
        $mail->Body = '
    <html>
    <head>
        <style>
        @keyframes bounce {
            0%, 100% {
                transform: translateY(-5px);
            }
            50% {
                transform: translateY(5px);
            }
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #a8e6cf, #dcedc1);
            transition: background-color 5s;
            height:700px;
        }
        .card {
            padding: 20px;
            width: 400px;
            min-height: 700px;
            border-radius: 20px;
            background: #e8e8e8;
            box-shadow: 5px 5px 6px #dadada,
                        -5px -5px 6px #f6f6f6;
            transition: 0.4s;
            margin-left:10%
        }
        img {
                width: 200px;
                height: auto;
                margin-top: 40px;
                margin-left:80px;  
                
            }
        .card:hover {
        translate: 0 -10px;
        }
        
        .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2e54a7;
        margin: 15px 0 0 10px;
        }
        .reason{
            color:red;
        }
        
        .card-image {
        min-height: 170px;
        background-color: #cfcfcf;
        border-radius: 15px;
        box-shadow: inset 8px 8px 10px #c3c3c3,
                    inset -8px -8px 10px #cfcfcf;
        }
        
        .card-body {
        margin: 13px 0 0 10px;
        color: rgb(31, 31, 31);
        font-size: 14.5px;
        }
        
        .footer {
        float: right;
        margin: 28px 0 0 18px;
        font-size: 13px;
        color: #636363;
        }
        
        .by-name {
        font-weight: 700;
        }
        
        @keyframes bounce {
                0%, 100% {
                    transform: translateY(-5px);
                }
                50% {
                    transform: translateY(5px);
                }
            }
        
        ul {
            list-style-type: none;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }
        a {
            color: #337ab7;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        a:hover {
            color: #ff8b94;
        }
        h2 {
            font-family: Roboto, sans-serif;
            font-size: 24px;
        }
        li {
            display: flex;
            align-items: center;
            transition: transform 0.3s;
            grid-column: span 2;
        }
        i {
            margin-right: 10px;
        }
        li:hover {
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
        body:hover {
            background-color: #dcedc1;
        }
        .card {
            padding: 20px;
            width: 350px;
            min-height: 800px;
            margin-left:0%
          }
        img {
            width: 200px;
            height: auto;
            margin-top: 40px;
            margin-left:30px;  
            
        }
        }
        </style>
        <link rel="stylesheet" href="https://www.bootstrapcdn.com/fontawesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="card">
            <div class="card-image">
                <img src="https://res.cloudinary.com/da8hdfiix/image/upload/v1690793326/profile/djyiwphuexckf0gkryxh.png" alt="Ebidir Logo" loading="lazy">
            </div>
            <p class="card-title">Hi ' . $recipientName . '</p>
            <p class="card-body">You have made a purchase via our agents.</p>
            <p class="card-body">Thank You for choosing E-bidir</p>
            <p class="card-body">We\'re here for you if you need support:</p>
            <p class="footer">Call us on: <span class="by-name">+251 925 882-8232</span></p>
            <p class="footer">Email us on: <span class="by-name"><a href="mailto:support@e-bidir.com">support@e-bidir.com</a></span></p>
            <p class="footer">Thank you for choosing Ebidir™.</p>
        </div>
    </body>
    </html>';

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "')</script>";
    }
}
