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
//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                          //Enable verbose debug output - Echos to output & causes SyntaxError, only use if viewing network log
        $mail->isSMTP();                                                //Send using SMTP
        $mail->Helo = 'csb';                                        // gmail blacklisted localhost

        $mail->Host = $_POST['email_host'];                                   //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                         //Enable SMTP authentication
        $mail->Username = $_POST['email_username'];                           //SMTP username
        $mail->Password = $_POST['email_password'];                           //SMTP password
        $mail->SMTPSecure = strtoupper($_POST['email_encryption']) == "TLS" ? //Enable implicit TLS encryption
            PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $_POST['email_port'];                                   //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

        //Recipients
        $mail->setFrom($_POST['email_from'], 'CSB7.0 Server');
        $mail->addAddress($_POST['rescue_email']);                            //Name is optional

        //Content
        $mail->isHTML(true);                                      //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
            echo json_encode(array('result' => false, 'message' => "Test Email failed: Something went wrong.", 'debug' => $mail->ErrorInfo));
        } else {
            echo json_encode(array('result' => true));
        }
    } catch (Exception $e) {
        echo json_encode(array('result' => false, 'debug' => $e, 'message' => "Test Email failed: " . $mail->ErrorInfo));
    }
} else {
    echo json_encode(array('result' => false, 'message' => "Test Email failed: Empty request."));
}
