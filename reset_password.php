<?php
session_start();
include "./connect.php";

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer files
require './assets/PHPMailer/PHPMailer.php';
require './assets/PHPMailer/SMTP.php';
require './assets/PHPMailer/Exception.php';

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
            $_SESSION['error'] = "Password does not meet the requirements.";
            header("Location: reset_password.php?token=$token");
            exit();
        }
    }
} else {
    $_SESSION['error'] = "Invalid or missing token!";
    header("Location: forgotpassword.php");
    exit();
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
        $mail->Password = 'krqhhnyvdbjoqgkl'; // SMTP password
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
?>





<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Forgot Password Basic - Pages | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->
    <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="error-toast">
        <div class="toast-header">
            <i class="bx bx-error me-2"></i> <!-- Add an error icon if you have one -->
            <div class="me-auto toast-title fw-semibold">Error</div>
            <small></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>

    <div class="container-xxl">
        <div class="bs-toast toast toast-placement-ex m-2 bg-primary top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="success-toast">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">success</div>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs>
                                            <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                                            <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                                            <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                                            <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                                        </defs>
                                        <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                                <g id="Icon" transform="translate(27.000000, 15.000000)">
                                                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                        <mask id="mask-2" fill="white">
                                                            <use xlink:href="#path-1"></use>
                                                        </mask>
                                                        <use fill="#696cff" xlink:href="#path-1"></use>
                                                        <g id="Path-3" mask="url(#mask-2)">
                                                            <use fill="#696cff" xlink:href="#path-3"></use>
                                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                        </g>
                                                        <g id="Path-4" mask="url(#mask-2)">
                                                            <use fill="#696cff" xlink:href="#path-4"></use>
                                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                        </g>
                                                    </g>
                                                    <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                        <use fill="#696cff" xlink:href="#path-5"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder">E-bidir Asbeza</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Change Password? 🔒</h4>
                        <p class="mb-4">Enter your new password and Confirm Yourpassword password</p>
                        <form id="formAuthentication" class="mb-3" action="reset_password.php?token=<?php echo $token; ?>" method="POST">
                            <div class="mb-3">
                                <label for="newpassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Enter your password" autofocus />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" autofocus />
                            </div>
                            <button type="submit" id="submit-btn" name="change_password" class="btn btn-primary d-grid w-100">Change Password</button>
                        </form>
                        <div class="text-center">
                            <a href="index.php" onclick="return validateForm()" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- 
<div class="buy-now">
    <a href="https://ThemeSelection.com/products/ThemeSelection-bootstrap-html-admin-template/" target="_blank"
      class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
  </div>
-->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <script src="./assets/js/common.js"></script>

    <!-- Vendors JS -->


    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submit-btn');

        function validateForm(event) {
            var newPassword = document.getElementById("newpassword").value;
            var confirmPassword = document.getElementById("confirmpassword").value;
            var passwordError = document.getElementById("password-error");

            // Check password length
            if (newPassword.length < 8) {
                event.preventDefault();
                displayError("Password must be at least 8 characters long.");
                return false;
            }

            // Check for at least one uppercase letter, one lowercase letter, and one digit
            var uppercaseRegex = /[A-Z]/;
            var lowercaseRegex = /[a-z]/;
            var digitRegex = /[0-9]/;

            if (!uppercaseRegex.test(newPassword) || !lowercaseRegex.test(newPassword) || !digitRegex.test(newPassword)) {
                event.preventDefault();
                displayError("Password must contain at least one uppercase letter, one lowercase letter, and one digit.");
                return false;
            }

            // Check if passwords match
            if (newPassword !== confirmPassword) {
                event.preventDefault();
                displayError("Passwords do not match.");
                return false;
            }

            // Clear any previous error messages
            passwordError.innerHTML = "";
            form.submit();
            return true; // Submit the form
        }
        // Function to display an error message in the toast
        function displayError(errorMessage) {
            var errorToast = document.getElementById("error-toast");
            var toastBody = errorToast.querySelector(".toast-body");
            toastBody.innerHTML = errorMessage;

            var bsToast = new bootstrap.Toast(errorToast);
            bsToast.show();
        }
        submitBtn.addEventListener('click', validateForm);
    </script>
</body>



</html>