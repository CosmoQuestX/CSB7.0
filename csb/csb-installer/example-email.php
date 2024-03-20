<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../../vendor/autoload.php";

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

if (isset($_POST) && !empty($_POST)) {
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp-relay.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'cosmoquestx@psi.edu';                     //SMTP username
        $mail->Password   = 'bawkhrgqkcjkhsii';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

        //Recipients
        $mail->setFrom('cosmoquestx@psi.edu', 'Mailer');
        $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
        $mail->addAddress('starstryder@gmail.com');               //Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

    echo 'Message has been sent';
    } catch (Exception $e) {
//        echo json_encode(array('result' => false, 'debug' => $e, 'message' => "Test Email failed: " . $mail->ErrorInfo));
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo json_encode(array('result' => false, 'message' => "Test Email failed: Empty request."));
}
