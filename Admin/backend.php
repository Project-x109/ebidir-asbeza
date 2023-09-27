<?php
include "../connect.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets./PHPMailer/PHPMailer.php';
require '../assets./PHPMailer/SMTP.php';
require '../assets./PHPMailer/Exception.php';

session_start();

if (isset($_POST['add_user'])) {
    // Validation functions
    function validatePhone($phone)
    {
        // Use the provided regex pattern to validate phone numbers
        $pattern = '/(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/';
        return preg_match($pattern, $phone);
    }

    function validateEmail($email)
    {
        // Use the provided regex pattern to validate emails
        $pattern = '/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i';
        return preg_match($pattern, $email);
    }

    function validateName($name)
    {
        // Use regex to validate that the name contains only letters and spaces
        return preg_match('/^[A-Za-z\s]+$/', $name);
    }

    function validateTINNumber($tinNumber)
    {
        // Use regex to validate that TIN number contains exactly 10 digits
        return preg_match('/^\d{10}$/', $tinNumber);
    }

    function validateJobStatus($jobStatus)
    {
        // Define an array of valid job statuses
        $validStatuses = array('Employed', 'Unemployed', 'Self Employed');
        return in_array($jobStatus, $validStatuses);
    }

    function validateAge($dob)
    {
        // Calculate age from date of birth
        $dobTimestamp = strtotime($dob);
        $todayTimestamp = time();
        $age = date('Y', $todayTimestamp) - date('Y', $dobTimestamp);
        if (date('md', $todayTimestamp) < date('md', $dobTimestamp)) {
            $age--;
        }
        return $age >= 18;
    }

    function validateImage($file)
    {
        $allowedExtensions = array("jpg", "jpeg", "png");
        $maxFileSize = 1024 * 1024; // 1MB

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileSize = $file['size'];

        return in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize;
    }

    $filename = $_FILES["profile"]["name"];
    $tempname = $_FILES["profile"]["tmp_name"];
    $date = date("s-h-d-m-Y");
    $folder = "../images/" . $date . $filename;

    // Check validation for phone, email, name, TIN number, job status, age, and image
    if (
        validatePhone($_POST['phone']) &&
        validateEmail($_POST['email']) &&
        validateName($_POST['name']) &&
        validateTINNumber($_POST['TIN_Number']) &&
        validateJobStatus($_POST['Job_Status']) &&
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
        $job_status = mysqli_real_escape_string($conn, $_POST['Job_Status']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $status = 'waiting';

        // Hash and salt the password
        $password = password_hash($randomPassword, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users`(`name`, `dob`, `phone`, `password`, `role`, `TIN_Number`, `profile`, `job_status`, `status`, `email`) 
                VALUES ('$name', '$dob', '$phone', '$password', 'user', '$TIN_Number', '$folder', '$job_status', '$status', '$email')";
        $res = $conn->query($sql);

        if ($res) {
            if (move_uploaded_file($tempname, $folder)) {
                // Send an email to the user with their unhashed password
                sendPasswordEmail($email, $randomPassword);

                $_SESSION['success'] = "User created Successfully";
                header("Location: addusers.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to upload image";
            }
        } else {
            $_SESSION['error'] = "Error creating user: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Validation failed for one or more fields.";
    }
}

// Handle errors (display them if needed)
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "')</script>";
    unset($_SESSION['error']); // Clear the error message
}

// Function to generate a random password
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
function sendPasswordEmail($recipientEmail, $password)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'amanuelgirma108@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'gnojaxeqnsdekijh'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Port may vary depending on the service

        // Sender info
        $mail->setFrom('amanuelgirma108@gmail.com', 'E-Bidir'); // Replace with your name and email

        // Recipient
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Password';
        $mail->Body = 'Your password is: ' . $password;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "')</script>";
    }
}
