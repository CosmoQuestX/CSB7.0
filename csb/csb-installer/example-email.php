<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../../vendor/autoload.php";

// Build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = $v->email_host;                 //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = $v->email_username;             //SMTP username
    $mail->Password = $v->email_password;             //SMTP password
    $mail->SMTPSecure = strtoupper($v->email_encryption) == "TLS" ?
        PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = $v->email_port;                 //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    //Recipients
    $mail->setFrom($v->email_from, 'CSB7.0 Server');
    $mail->addAddress($v->rescue_email);                           //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo json_encode(array('result' => true));
//    echo 'Message has been sent';
} catch (Exception $e) {
    echo json_encode(array('result' => false, 'message' => "Test Email failed: " . $e));
//    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
