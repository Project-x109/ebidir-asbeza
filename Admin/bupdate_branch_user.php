<?php
include "../common/ratelimiter.php";
include "../connect.php";

include "../user/functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';
require '../assets/PHPMailer/Exception.php';

if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['token']) {
    echo json_encode(['error' => 'Authorization Error']);
    exit;
}
// Get POST data
$userId = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$status = $_POST['status'];
$phone = $_POST['phone'];

// Initialize response array
$response = array();

// Create an array to store validation errors
$validationErrors = array();

// Check if the name is empty or invalid
if (!validateName($name)) {
    $validationErrors[] = "Name can only contain letters and spaces.";
}

// Check if the email is empty or invalid
if (!validateEmail($email)) {
    $validationErrors[] = "Invalid email address.";
}

// Check if the phone is empty or invalid
if (!validatePhone($phone)) {
    $validationErrors[] = "Invalid phone number format.";
}

// Check if there are validation errors
if (!empty($validationErrors)) {
    $response['status'] = 'error';
    $response['message'] = 'Validation error(s)';
    $response['errors'] = $validationErrors;
} else {
    // Check if the email is already registered by another user
    $emailQuery = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
    $stmtEmail = $conn->prepare($emailQuery);
    $stmtEmail->bind_param("ss", $email, $userId);
    $stmtEmail->execute();
    $stmtEmail->store_result();

    // Check if the phone number is already registered by another user
    $phoneQuery = "SELECT user_id FROM users WHERE phone = ? AND user_id != ?";
    $stmtPhone = $conn->prepare($phoneQuery);
    $stmtPhone->bind_param("ss", $phone, $userId);
    $stmtPhone->execute();
    $stmtPhone->store_result();

    if ($stmtEmail->num_rows > 0) {
        $validationErrors[] = "Email is already registered by another user.";
    }

    if ($stmtPhone->num_rows > 0) {
        $validationErrors[] = "Phone number is already registered by another user.";
    }
    // Update user data

    if (!empty($validationErrors)) {
        $response['status'] = 'error';
        $response['message'] = 'Validation error(s)';
        $response['errors'] = $validationErrors;
    } else {
        $attempt = 0;
        $queryUser = "UPDATE users SET name = ?, email = ?, status = ?, phone = ?,attempt=? WHERE user_id = ?";
        $stmtUser = $conn->prepare($queryUser);

        if (!$stmtUser) {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing the SQL statement for user data update: ' . $conn->error;
        } else {
            $stmtUser->bind_param("ssssis", $name, $email, $status, $phone, $attempt, $userId);

            if ($stmtUser->execute()) {
                // User data updated successfully
                $response['status'] = 'success';
                insertLog($conn, $_SESSION['id'], "User with user ID " . $userId . "Have been Updated");
                /*  sendProfileUpdatedEmail($email, $name, $conn); */
            } else {
                // User data update failed
                insertLog($conn, $_SESSION['id'], "Unable to update user with user ID " . $userId . " Reason " . $stmtUser->error);
                $response['status'] = 'error';
                $response['message'] = 'Error updating user data: ' . $stmtUser->error;
            }

            $stmtUser->close();
        }
    }
    $stmtEmail->close();
    $stmtPhone->close();
}

// Close database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

function sendProfileUpdatedEmail($recipientEmail, $name, $conn)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'elite.ethiopia@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'yvhjizpgcmvypjdu'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Port may vary depending on the service
        $recipientQuery = "SELECT  name FROM users WHERE email = '$recipientEmail'";
        $recipientResult = $conn->query($recipientQuery);
        if ($recipientResult->num_rows > 0) {
            $row = $recipientResult->fetch_assoc();
            $recipientName = $row['name'];
            // Sender info
            $mail->setFrom('elite.ethiopia@gmail.com', 'E-Bidir'); // Replace with your name and email
            $mail->addAddress($recipientEmail, $recipientName); // Add recipient from the database
        } else {
            // If the token doesn't match any user, handle the error
            throw new Exception("Recipient not found in the database.");
        }
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Profile Updated';
        $loginlink = "http://asbeza.ebidir.net/index.php";
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
            <p class="card-body">Your E-bidir Asbeza Account has been Updated.</p>
            <p class="card-body">Your Profile Information Has been Updated </p>
            <p class="card-body">Login to your account with provided Link and See the changes that have been made to your profile<a href=' . $loginlink . '> Click Here to login</a></p>
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
