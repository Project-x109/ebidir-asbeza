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
    $mail->setFrom('amanuelgirma108@gmail.com', $subject . 'Has Sent You a Message'); // Replace with your name
    $mail->addAddress('amanuelgirma108@gmail.com');
    $mail->Subject = $subject;
    $mail->Body = $emailBody;

    // Send email using PHPMailer
    if ($mail->send()) {
        // Email sent successfully
        echo "Email sent successfully.";
    } else {
        // Email sending failed
        echo "Email sending failed: " . $mail->ErrorInfo;
    }
} else {
    // If the request method is not POST
    echo "Invalid request method.";
}
