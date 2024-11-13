<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Make sure this path is correct if you are using Composer

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'n.silvagan@gmail.com';  // Your email address
        $mail->Password = 'yhes onpo gngh yofw';    // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('n.silvagan@gmail.com', 'Admin');  // Sender's email
        $mail->addAddress($to);  // Recipient's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;  // Email sent successfully
    } catch (Exception $e) {
        return false;  // Error sending email
    }
}
?>
