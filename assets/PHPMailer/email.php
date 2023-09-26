<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $recipientEmail = $_POST["recipient_email"];
    $contactNumber = $_POST["contact_number"];
    $message = $_POST["email_message"];

    // Combine first name and last name as the email subject
    $subject = $firstName . " " . $lastName;

    // Set additional headers with the 'From' address
    $headers = "From: amanuelgirma108@gmail.com\r\n";
    $headers .= "Reply-To: amanuelgirma108@gmail.com\r\n";

    // Configure SMTP settings
    $smtpServer = 'smtp.gmail.com';
    $smtpUsername = 'amanuelgirma108@gmail.com';
    $smtpPassword = 'arrtqumqvzsdskar'; // Replace with your App Password
    $smtpPort = 587; // Port may vary depending on the service

    // Set the 'From' address for SMTP
    ini_set("sendmail_from", "amanuelgirma108@gmail.com");

    // Create the email body
    $emailBody = "Email: $recipientEmail\n";
    $emailBody .= "Contact Number: $contactNumber\n\n";
    $emailBody .= "Message:\n$message";

    // Set the SMTP configuration
    $smtpConfig = [
        'auth' => 'login',
        'username' => $smtpUsername,
        'password' => $smtpPassword,
        'port' => $smtpPort,
        'smtpSecure' => 'tls', // Use TLS encryption
    ];

    // Create an instance of PHPMailer (you need to download and include PHPMailer)
    require 'PHPMailer.php';
    require 'SMTP.php';
    require 'Exception.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // Use SMTP
    $mail->isSMTP();
    $mail->Host = $smtpServer;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = $smtpConfig['smtpSecure'];
    $mail->Port = $smtpConfig['port'];

    // Set email details
    $mail->setFrom('amanuelgirma108@gmail.com', $subject . ' Has Sent You a Message'); // Replace with your name
    $mail->addAddress('amanuelgirma108@gmail.com');
    $mail->Subject = $subject;
    $mail->Body = $emailBody;
} else {
    // If the request method is not POST
    echo "Invalid request method.";
}

?>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="./email.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1  container-p-y">

                    <?php

                    if ($mail->send()) {
                        // Email sent successfully
                        echo ' 
                        <div class="notificationCard">
                            <p class="notificationHeading">Email Sent Successfully</p>
                            <svg  class="bellIcon" viewBox="0 0 448 512">
                                <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"></path>
                            </svg>
                            <p class="notificationPara">
                            Thank you for reaching out to us! We are pleased to inform you that your email 
                            has been sent successfully.If necessary, we will get back to you as soon as possible.</p>
                            <div class="buttonContainer">
                            <button class="AllowBtn"><a href = "../../index.php">Login</a></button>
        
                            </div>
                        </div>';
                    } else {
                        // Email sending failed
                        echo '
                        <div class="notificationCard">
                        <p class="notificationHeading">Error While Sending Email</p>
                        <svg  class="bellIconerror" viewBox="0 0 448 512">
                            <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"></path>
                        </svg>
                        <p class="notificationPara">
                        Sorry, we encountered an error while attempting to send your message. Please double-check your 
                        information and try again later
                        </p>
                        <div class="buttonContainer">
                            <button class="AllowBtn"><a href = "../../index.php">Login</a></button>
    
                        </div>
                    </div>'
                            . $mail->ErrorInfo;
                    }

                    ?>
                </div>
            </div>
        </div>


        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>
    <script src="../assets/js/mark-Notification-read.js"></script>
    <script src="../assets/js/usercredithistory.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>