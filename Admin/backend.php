<?php
include "../connect.php";
include "../User/functions.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';
require '../assets/PHPMailer/Exception.php';

session_start();




$validationErrors = array();
$response = array();

if (isset($_POST['add_user'])) {


    // Check if the phone number, TIN number, and email are already used
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $TIN_Number = mysqli_real_escape_string($conn, $_POST['TIN_Number']);

    $phoneQuery = "SELECT * FROM `users` WHERE `phone`='$phone'";
    $emailQuery = "SELECT * FROM `users` WHERE `email`='$email'";
    $TINQuery = "SELECT * FROM `users` WHERE `TIN_Number`='$TIN_Number'";

    $phoneResult = $conn->query($phoneQuery);
    $emailResult = $conn->query($emailQuery);
    $TINResult = $conn->query($TINQuery);

    if ($phoneResult->num_rows > 0) {
        $validationErrors[] = "Phone number is already registered.";
    }
    if ($emailResult->num_rows > 0) {
        $validationErrors[] = "Email is already registered.";
    }

    if ($TINResult->num_rows > 0) {
        $validationErrors[] = "TIN number is already registered.";
    }
    // Check individual validation conditions and add specific errors to the array
    if (!validatePhone($_POST['phone'])) {
        $validationErrors[] = "Invalid phone number format.";
    }

    if (!validateEmail($_POST['email'])) {
        $validationErrors[] = "Invalid email address.";
    }

    if (!validateName($_POST['name'])) {
        $validationErrors[] = "Name can only contain letters and spaces.";
    }

    if (!validateTINNumber($_POST['TIN_Number'])) {
        $validationErrors[] = "Invalid TIN number format.";
    }

    if (!validateAge($_POST['dob'])) {
        $validationErrors[] = "You must be at least 18 years old.";
    }

    if (!validateImage($_FILES["profile"])) {
        $validationErrors[] = "Invalid image format or file size exceeds the limit.";
    }

    if (
        validatePhone($_POST['phone']) &&
        validateEmail($_POST['email']) &&
        validateName($_POST['name']) &&
        validateTINNumber($_POST['TIN_Number']) &&
        validateAge($_POST['dob']) &&
        validateImage($_FILES["profile"])
    ) {
        // Generate a random password
        $randomPassword = generateRandomPassword();

        // Sanitize user inputs
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $TIN_Number = mysqli_real_escape_string($conn, $_POST['TIN_Number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $status = 'waiting';

        // Hash and salt the password
        $password = password_hash($randomPassword, PASSWORD_DEFAULT);

        $filename = $_FILES["profile"]["name"];
        $tempname = $_FILES["profile"]["tmp_name"];
        $date = date("s-h-d-m-Y");
        $folder = "../images/" . $date . $filename;

        $sql = "INSERT INTO `users`(`name`, `dob`, `phone`, `password`, `role`, `TIN_Number`, `profile`, `status`, `email`) 
                VALUES ('$name', '$dob', '$phone', '$password', 'user', '$TIN_Number', '$folder', '$status', '$email')";
        $res = $conn->query($sql);

        if ($res) {
            if (move_uploaded_file($tempname, $folder)) {
                // Send an email to the user with their unhashed password
                sendPasswordEmail($email, $randomPassword, $conn);

                $_SESSION['success'] = "User created successfully";
                // Set the success message in the response
                $response = array('success' => $_SESSION['success']);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            } else {
                $_SESSION['error'] = "Failed to upload image";
            }
        } else {
            $_SESSION['error'] = "Error creating user: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = implode("<br>", $validationErrors);
    }

    if (!empty($validationErrors)) {
        // Validation errors occurred
        $response = array('errors' => $validationErrors);
    } else {
        // No errors, success response
        $response = array('success' => 'User created successfully');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Handle errors (display them in the toast if needed)
/* if (isset($_SESSION['error'])) {
  echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
            errorToast.show();
            document.querySelector("#error-toast .toast-body").innerHTML = "' . $_SESSION['error'] . '";
        });
      </script>';
  unset($_SESSION['error']); // Clear the error message
} */
// Function to generate a random password










if (isset($_POST['addbranch'])) {
    // Validation functions (similar to those in add_users)
    $response = array();
    $branchValidationErrors = array();

    // Check if the phone number and email are already used
    $branchPhone = mysqli_real_escape_string($conn, $_POST['phonenumber']);
    $branchEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $branchname = mysqli_real_escape_string($conn, $_POST['branch_name']);

    $phoneQuery = "SELECT * FROM `users` WHERE `phone`='$branchPhone'";
    $emailQuery = "SELECT * FROM `users` WHERE `email`='$branchEmail'";
    $branchnameQuery = "SELECT * FROM `users` WHERE `name`='$branchname'";

    $phoneResult = $conn->query($phoneQuery);
    $emailResult = $conn->query($emailQuery);
    $branchNameResult = $conn->query($branchnameQuery);

    if ($phoneResult->num_rows > 0) {
        $branchValidationErrors[] = "Phone number is already registered.";
    }
    if ($emailResult->num_rows > 0) {
        $branchValidationErrors[] = "Email is already registered.";
    }
    if ($branchNameResult->num_rows > 0) {
        $branchValidationErrors[] = "Branch is already registered.";
    }

    // Additional validation for branch-specific fields (e.g., branch name, location)

    // Check if there are any validation errors
    if (empty($branchValidationErrors)) {
        // Generate a random password
        $randomPassword = generateRandomPassword();

        // Sanitize user inputs
        $branchName = mysqli_real_escape_string($conn, $_POST['branch_name']);
        $branchPhone = mysqli_real_escape_string($conn, $_POST['phonenumber']);
        $branchEmail = mysqli_real_escape_string($conn, $_POST['email']);
        $branchLocation = mysqli_real_escape_string($conn, $_POST['location']);

        // Hash and salt the password
        $password = password_hash($randomPassword, PASSWORD_DEFAULT);

        // Generate a unique branch ID (e.g., "EB0001")
        $sql = "SELECT * FROM admin_setting";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        $branch_id = "EB" . ($row ? sprintf('%04d', $row['branch']) : '0000');

        // Insert branch information into the 'users' table
        $sql = "INSERT INTO `users`(`name`, `phone`, `password`, `role`, `status`, `email`, `user_id`) 
                VALUES ('$branchName', '$branchPhone', '$password', 'branch', 'waiting', '$branchEmail', '$branch_id')";
        $res = $conn->query($sql);

        if ($res) {
            // Update the branch counter in the 'admin_setting' table
            $sql = "UPDATE admin_setting SET branch=" . ($row['branch'] + 1);
            $conn->query($sql);

            // Insert branch information into the 'branch' table
            $sql = "INSERT INTO `branch`(`branch_name`, `location`, `branch_id`)
                    VALUES ('$branchName','$branchLocation','$branch_id')";
            $res = $conn->query($sql);

            if ($res) {
                // Send an email with the generated password to the branch
                sendPasswordEmail($branchEmail, $randomPassword, $conn);
                $_SESSION['success'] = "Branch Account created successfully";
                $response = array('success' => $_SESSION['success']);
            } else {
                $_SESSION['error'] = "Error Occurred";
                $response = array('errors' => $_SESSION['error']);
            }
        } else {
            $_SESSION['error'] = "Error Occurred";
        }
    } else {
        $response = array('errors' => $branchValidationErrors);
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
// Always return a JSON response




function generateRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Function to send an email with the password
function sendPasswordEmail($recipientEmail, $password, $conn)
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
            <p class="card-body">Your E-bidir Asbeza Account has been created.</p>
            <p class="card-body">Your Login password is ' . $password . '</p>
            <p class="card-body">Login to your account with provided Link and Change Your Password<a href=' . $loginlink . '> Click Here to login</a></p>
            <p class="card-body">Please Dont Share this Password with anyone even if tehy say they are rom E-bidir.</p>
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





/* if (isset($_POST['addbranch'])) {

    $sql = "SELECT * FROM admin_setting";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $branch_id = "EB" . sprintf('%04d', $row['branch']);
    $sql = "INSERT INTO `users`(`name`, `phone`, `password`, `role`, `status`, `email`,`user_id`) 
    VALUES ('$_POST[branch_name]', '$_POST[phonenumber]', '123', 'branch', 'waiting','$_POST[email]','$branch_id')";
    $res = $conn->query($sql);
    if ($res) {
        $sql = "UPDATE admin_setting set branch=" . ($row['branch'] + 1);
        $conn->query($sql);
        $sql = "INSERT INTO `branch`(`branch_name`,`location`,`branch_id`)
    VALUES ('$_POST[branch_name]','$_POST[location]','$branch_id')";
        $res = $conn->query($sql);
        if ($res) {
            header("location:addbranch.php");
            $_SESSION['success'] = "Branch Account created successfully";
        } else {
            header("location:addbranch.php");
            $_SESSION['error'] = "Error Occured";
        }
    }
    header("location:addbranch.php");
} */