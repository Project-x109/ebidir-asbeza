<?php
session_start();
include "connect.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './assets/PHPMailer/PHPMailer.php';
require './assets/PHPMailer/SMTP.php';
require './assets/PHPMailer/Exception.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone']) && isset($_POST['password'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $userEnteredPassword = $_POST['password'];

    // Retrieve the user data from the database based on the phone number
    $sql = "SELECT * FROM `users` WHERE phone = '$phone'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            $status = $row['status'];

            // Verify the user-entered plain text password against the retrieved hashed password
            if (password_verify($userEnteredPassword, $hashedPassword)) {
                $_SESSION['role'] = $row['role'];
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['dob'] = $row['dob'];
                $_SESSION['credit_limit'] = $row['credit_limit'];
                $_SESSION['level'] = $row['level'];

                    // Check user status
                    if ($status === 'waiting') {
                        $_SESSION['status'] = 'waiting'; // Set a session variable to indicate the status

                        // Redirect to change password page
                        header("location: newpassword.php");
                        exit();
                    } elseif ($status === 'active') {
                        // User is already active, redirect to the appropriate dashboard
                        $loc = $_SESSION['role'] . "/";
                        header("location: " . $loc);
                        exit();
                    }
                } else {
                    // Password is incorrect, store error message in session
                    $_SESSION['error'] = "Password is incorrect";
                    header("Location: index.php");
                    exit();
                }
            }
        } else {
            // User with this phone number does not exist, store error message in session
            $_SESSION['error'] = "User with this phone number does not exist";
            header("Location: index.php");
            exit();
        }
    } else {
        // MySQL Error, store error message in session
        $_SESSION['error'] = "MySQL Error: " . mysqli_error($conn);
        header("Location: index.php");
        exit();
    }
} /* else {
    header("location: index.php");
    exit();
}
 */




//forgotpassword
if (isset($_POST['forgot_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // Check if the email exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);
    if ($checkEmailResult->num_rows > 0) {

        // Generate a unique token
        $token = bin2hex(random_bytes(16));

        // Calculate the expiration time (e.g., 1 hour from now)
        $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert the token into the database
        $updateTokenQuery = "UPDATE users SET token = '$token', expire_time = '$expireTime' WHERE email = '$email'";
        $updateTokenResult = $conn->query($updateTokenQuery);

        if ($updateTokenResult) {
            // Send an email to the user with the reset password link
            $resetLink = "http://localhost/sneat-bootstrap-html-admin-template-free/reset_password.php?token=$token";

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'amanuelgirma108@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'fyupdrokktlcghpb'; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587; // Port may vary depending on the service
                $recipientQuery = "SELECT  name FROM users WHERE email = '$email'";
                $recipientResult = $conn->query($recipientQuery);
                if ($recipientResult->num_rows > 0) {
                    $row = $recipientResult->fetch_assoc();
                    $recipientName = $row['name'];

                    // Recipients
                    $mail->setFrom('amanuelgirma108@gmail.com', 'E-bidir');
                    $mail->addAddress($email, $recipientName); // Add recipient from the database
                } else {
                    // If the token doesn't match any user, handle the error
                    throw new Exception("Recipient not found in the database.");
                }
                // Content
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

// ... (the rest of your code)

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
// Add this part to display an error alert if $_SESSION['error'] is set
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
    // Check if the token has expired (e.g., 24 hours)
    $expireTime = 3600; // 24 hours in seconds
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
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password in the database using the token
                $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE token = '$token'";
                $updatePasswordResult = $conn->query($updatePasswordQuery);

                if ($updatePasswordResult) {
                    // Regenerate the token after successful reset
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




// Function to validate password strength, complexity, and length
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

// Function to generate a random token
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
        $mail->Username = 'amanuelgirma108@gmail.com'; // SMTP username
        $mail->Password = 'kvwgyeutctlizikd'; // SMTP password
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
            $mail->setFrom('amanuelgirma108@gmail.com', 'E-bidir');
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

// ... (the rest of your code)

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
