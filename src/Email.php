<?php

namespace CosmoQuestX;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email
{

    public static function sendEmail(string $to, string $subject, string $body): bool
    {

        $emailSettings = EmailSettings::getEmailSettings();

        $mail = new PHPMailer(true);
        try {
            include
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = $emailSettings['host'];                 // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $emailSettings['username'];             // SMTP username
            $mail->Password   = $emailSettings['password'];             // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = $emailSettings['port'];                 // TCP port to connect to
            $mail->addAddress($to);                                     // Add a recipient
            $mail->setFrom($emailSettings['from'], 'Mailer');
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
            return TRUE;
        }
        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return FALSE;
        }
    }

    public static function checkSendMail()
    {
        $command = "sendmail -v -q";
        exec($command, $output, $errCode);
        return $errCode == 0 ? TRUE : FALSE;
    }

}
