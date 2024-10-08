<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../../vendor/autoload.php";

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Helo = 'csb';                                        // gmail blacklisted localhost

    $mail->Host = $emailSettings['host'];                       //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                     //Enable SMTP authentication
    $mail->Username = $emailSettings['username'];               //SMTP username
    $mail->Password = $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $emailSettings['port'];                       //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    //Recipients
    $mail->setFrom($emailSettings['from'], 'CSB7.0 Server');

    //Content
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = "Your CSB Admin Credentials";
    $mail->Body = "<a href='$BASE_URL'>Here</a> is your new site!<br><br>".
        "<b>Admin Username:</b> <code>$username</code><br>".
        "<b>Admin Password</b>: <code>$password</code>";
    $mail->AltBody = "Here is your new site!\r\n\r\nURL: $BASE_URL\r\nUsername: $username\r\nPassword: $password";

    $mail->send();
} catch (Exception $e) {
    // It'll be alright
}
