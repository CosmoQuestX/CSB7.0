<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../../vendor/autoload.php";
function sendNewEmail($subject, $msg, $to, $emailSettings) {

    //Create an instance; passing true enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                          //Enable verbose debug output - Echos to output & causes SyntaxError, only use if viewing network log
        $mail->isSMTP();                                                //Send using SMTP
        $mail->Helo = 'csb';                                        // gmail blacklisted localhost

        $mail->Host       = $emailSettings['host'];                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $emailSettings['username'];                     //SMTP username
        $mail->Password   = $emailSettings['password'];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $emailSettings['port'];                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

        //Recipients
        $mail->setFrom($emailSettings['from'], 'Citizen Science Builder');
        $mail->addAddress($to);               //Name is optional

        //Content
        $mail->isHTML(true);                                      //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $msg;

        $mail->send();
        echo "good $to ".$emailSettings['from'];
    } catch (Exception $e) {
        echo json_encode(array('result' => false, 'message' => "Test Email failed: Malformed Statement"));
    }
    die();
}
