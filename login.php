<?php
include "connect.php";
include "./user/functions.php";
session_start();
$maxRequests = 60;
$perSecond = 60;
$ip = $_SERVER['REMOTE_ADDR'];
$identifier = md5($ip);
$storageDir = __DIR__ . '/rate_limit_storage/';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

$timestamp = time();
$expiration = $timestamp - $perSecond;
$files = scandir($storageDir);
foreach ($files as $file) {
    $filePath = $storageDir . $file;
    if (is_file($filePath) && filemtime($filePath) < $expiration) {
        unlink($filePath);
    }
}

$requests = 1; // Initial request
$files = scandir($storageDir);
foreach ($files as $file) {
    $filePath = $storageDir . $file;
    if (is_file($filePath)) {
        $data = file_get_contents($filePath);
        $requestData = json_decode($data, true);
        if ($requestData['ip'] === $ip) {
            $requests++;
        }
    }
}
if ($requests > $maxRequests) {
    http_response_code(429); // HTTP 429 Too Many Requests
    $_SESSION['error'] = "Too Many Requestes Try again Letter";
    header("Location: index.php");
    die("Too Many Requestes Try again Letter.");
}

// Record the client's request
$filename = $storageDir . $timestamp . '-' . $identifier . '.json';
$data = json_encode(['ip' => $ip, 'timestamp' => $timestamp]);
file_put_contents($filename, $data);

// Continue with your application logic
include "./common/jwt.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './assets/PHPMailer/PHPMailer.php';
require './assets/PHPMailer/SMTP.php';
require './assets/PHPMailer/Exception.php';
require './vendor/firebase/php-jwt/src/JWT.php';

$token = htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8');
if (!$token || $token !== $_SESSION['token']) {
    $_SESSION['error'] = "Authorization Error";
    header("Location: index.php");
    exit;
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone']) && isset($_POST['password'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $userEnteredPassword = $_POST['password'];
    $sql = "SELECT * FROM `users` WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            $status = $row['status'];
            $attempt = $row['attempt'];

            if ($attempt >= 3 && $status === 'dormant') {
                $_SESSION['error'] = "Your account is blocked due to multiple failed attempts.";
                insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login and failed due to multiple attempt");
                header("Location: index.php");
                exit();
            }
            if ($status === 'inactive') {
                $_SESSION['error'] = "Your account is blocked Contact Administrators.";
                insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login and failed becasue account is incactive");
                header("Location: index.php");
                exit();
            }
            if (password_verify($userEnteredPassword, $hashedPassword) && $status != "dormant") {
                $attempt = 0;
                $updateSql = "UPDATE `users` SET attempt = ? WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("is", $attempt, $row['user_id']);
                $updateStmt->execute();
                $_SESSION['role'] = $row['role'];
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['dob'] = $row['dob'];
                $_SESSION['credit_limit'] = $row['credit_limit'];
                $_SESSION['level'] = $row['level'];
                $tokenjwt = generateJWT($_SESSION['id'], $_SESSION['role']);
                $_SESSION['tokenjwt'] = $tokenjwt;
                setcookie('jwt_token', $tokenjwt, time() + 1800, '/');

                if ($status === 'waiting') {
                    $_SESSION['status'] = 'waiting';
                    header("location: newpassword.php");
                    exit();
                } elseif (strtolower($status) === 'active') {
                    $loc = $_SESSION['role'];
                    if ($_SESSION['role'] == "EA")
                        $loc = "Admin";
                    if ($_SESSION['role'] == "delivery")
                        $loc = "branch";
                    if ($_SESSION['role'] == "user") {
                        if (isset($_SESSION['temp_cart']) && !empty($_SESSION['temp_cart'])) {
                            if (!isset($_SESSION['cart'])) {
                                $_SESSION['cart'] = [];
                            }
                            $_SESSION['cart'] = array_merge($_SESSION['cart'], $_SESSION['temp_cart']);
                            unset($_SESSION['temp_cart']);
                            $loc .= "/loan.php";
                        }
                    }
                } else {
                    $loc .= "/";
                }
                header("location: " . $loc);
                insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login and was successfull");
                exit();
            } else {
                if ($status === 'dormant') {
                    $_SESSION['error'] = "Your account is blocked Contact Administrators.";
                    insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login and failed becasue account is dormant");
                    header("Location: index.php");
                    exit();
                }
                $_SESSION['attempt'] = $row['attempt'];
                $attempt = $_SESSION['attempt'];
                $attempt++;
                if ($attempt >= 3) {
                    $status = 'dormant';
                    $attempt = 3;
                    insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login more than three times and account had been blocked");
                }
                $updateSql = "UPDATE `users` SET attempt = ?, status = ? WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("iss", $attempt, $status, $row['user_id']);
                $updateStmt->execute();

                if ($attempt < 3) {
                    $_SESSION['error'] = "Password is incorrect. You have made " . $attempt . " attempts";
                    insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login and Password is incorrect. You have made " . $attempt . " attempts");
                } else {
                    $_SESSION['error'] = "Invalid login attempt. Your account may be blocked due to multiple failed attempts.";
                    insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has tried to login but account has been blocked due too multiple attempt");
                }
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "User with this phone number does not exist";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "An error occurred while processing your request";
        header("Location: index.php");
        exit();
    }
} else if (isset($_POST['forgot_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);
    $row = $checkEmailResult->fetch_assoc();

    insertLog($conn, $row['user_id'], "User with id {$row['user_id']} has made a forgot password request");

    if ($checkEmailResult->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $updateTokenQuery = "UPDATE users SET token = '$token', expire_time = '$expireTime' WHERE email = '$email'";
        insertLog($conn, $row['user_id'], "A token has been generated for User with id {$row['user_id']}");
        $updateTokenResult = $conn->query($updateTokenQuery);
        if ($updateTokenResult) {
            $resetLink = "http://asbeza.ebidir.net/reset_password.php?token=$token";
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'elite.ethiopia@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'asybyikorkslidwa'; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587; // Port may vary depending on the service
                $recipientQuery = "SELECT  name FROM users WHERE email = '$email'";
                $recipientResult = $conn->query($recipientQuery);
                if ($recipientResult->num_rows > 0) {
                    $row = $recipientResult->fetch_assoc();
                    $recipientName = $row['name'];
                    $mail->setFrom('elite.ethiopia@gmail.com', 'E-bidir');
                    $mail->addAddress($email, $recipientName);
                } else {
                    throw new Exception("Recipient not found in the database.");
                }
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
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
                    <p class="card-body">Your Password has been changed.</p>
                    <p class="card-body">Click the following link to reset your password: <a href=' . $resetLink . '>Click Here</a></p>
                    <p class="card-body">This is a One Time recovery Link.</p>
                    <p class="card-body">We\'re here for you if you need support:</p>
                    <p class="footer">Call us on: <span class="by-name">+251 925 882-8232</span></p>
                    <p class="footer">Email us on: <span class="by-name"><a href="mailto:support@e-bidir.com">support@e-bidir.com</a></span></p>
                    <p class="footer">Thank you for choosing Ebidir™.</p>
                </div>
            </body>
            </html>';

                // Send the email
                $mail->send();

                $_SESSION['success'] = "Password reset link sent to your email";
                header("Location: forgotpassword.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "Error inserting token into the database: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Email does not exist in the database";
        header("Location: forgotpassword.php");
        exit();
    }
}

if (isset($_SESSION['success'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var successToast = new bootstrap.Toast(document.getElementById("success-toast"));
            successToast.show();
            document.querySelector("#success-toast .toast-body").innerHTML = "' . $_SESSION['success'] . '";
        });
      </script>';
    unset($_SESSION['success']); // Clear the success message
}
if (isset($_SESSION['error'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
            errorToast.show();
            document.querySelector("#error-toast .toast-body").innerHTML = "' . $_SESSION['error'] . '";
        });
      </script>';
    unset($_SESSION['error']); // Clear the error message

}
//reset password
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $expireTime = 3600;
    $currentTime = time();

    $tokenQuery = "SELECT expire_time FROM users WHERE token = '$token'";
    $tokenResult = $conn->query($tokenQuery);

    if ($tokenResult->num_rows > 0) {
        $row = $tokenResult->fetch_assoc();
        $tokenExpireTime = strtotime($row['expire_time']);

        if ($tokenExpireTime < $currentTime) {
            $_SESSION['error'] = "Token has expired. Please request a new password reset.";
            header("Location: forgotpassword.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid token!";
        header("Location: forgotpassword.php");
        exit();
    }
    if (isset($_POST['change_password'])) {
        $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

        if (isValidPassword($newPassword)) {
            if ($newPassword === $confirmPassword) {
                $row = $tokenResult->fetch_assoc();
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE token = '$token'";
                $selectasswordQuery = "SELECT user_id from  users  WHERE token = '$token'";
                $selectasswordQueryResult = $conn->query($selectasswordQuery);
                $row1 = $selectasswordQueryResult->fetch_assoc();
                $updatePasswordResult = $conn->query($updatePasswordQuery);
                insertLog($conn, $row1['user_id'], "Password Has been changed for User with id {$row1['user_id']}");
                if ($updatePasswordResult) {
                    $newToken = generateRandomToken();
                    $updateTokenQuery = "UPDATE users SET token = '$newToken' WHERE token = '$token'";
                    $conn->query($updateTokenQuery);
                    sendEmailNotification($newToken, $conn);
                    $_SESSION['success'] = "Password has been changed successfully!";
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Password update failed: " . $conn->error;
                    header("Location: reset_password.php?token=$token");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Passwords do not match!";
                header("Location: reset_password.php?token=$token");
                exit();
            }
        } else {
            $_SESSION['error'] = "Password does not meet the requirements.Password should conatin one Capital,symbol,number and lowecase letter";
            header("Location: reset_password.php?token=$token");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or missing token!";
        header("Location: index.php");
        exit();
    }
}
function isValidPassword($password)
{
    // Check if the password meets the requirements
    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return false;
    }

    if (!preg_match("/[a-z]/", $password)) {
        return false;
    }

    if (!preg_match("/[0-9]/", $password)) {
        return false;
    }

    // All requirements met
    return true;
}
function generateRandomToken()
{
    return bin2hex(random_bytes(32));
}
function sendEmailNotification($newToken, $conn)
{
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'elite.ethiopia@gmail.com'; // SMTP username
        $mail->Password = 'wpewmjfquezekosh'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // TCP port to connect to
        // Get recipient's email address from the database using the token
        $recipientQuery = "SELECT email, name FROM users WHERE token = '$newToken'";
        $recipientResult = $conn->query($recipientQuery);
        if ($recipientResult->num_rows > 0) {
            $row = $recipientResult->fetch_assoc();
            $recipientEmail = $row['email'];
            $recipientName = $row['name'];

            // Recipients
            $mail->setFrom('elite.ethiopia@gmail.com', 'E-bidir');
            $mail->addAddress($recipientEmail, $recipientName); // Add recipient from the database
        } else {
            // If the token doesn't match any user, handle the error
            throw new Exception("Recipient not found in the database.");
        }

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Password Changed Successfully';
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
                <p class="card-body">Your Password has been changed.</p>
                <p class="card-body">:</p>
                <p class="card-body">Your password has been changed successfully. If you did not initiate this change, please contact us immediately.</p>
                <p class="card-body">We\'re here for you if you need support:</p>
                <p class="footer">Call us on: <span class="by-name">+251 925 882-8232</span></p>
                <p class="footer">Email us on: <span class="by-name"><a href="mailto:support@e-bidir.com">support@e-bidir.com</a></span></p>
                <p class="footer">Thank you for choosing Ebidir™.</p>
            </div>
        </body>
        </html>';

        // Send email
        $mail->send();
    } catch (Exception $e) {
        // Handle email sending errors
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
if (isset($_SESSION['success'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var successToast = new bootstrap.Toast(document.getElementById("success-toast"));
            successToast.show();
            document.querySelector("#success-toast .toast-body").innerHTML = "' . $_SESSION['success'] . '";
        });
      </script>';
    unset($_SESSION['success']); // Clear the success message
}

if (isset($_SESSION['error'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
            errorToast.show();
            document.querySelector("#error-toast .toast-body").innerHTML = "' . $_SESSION['error'] . '";
        });
      </script>';
    unset($_SESSION['error']); // Clear the error message
}
