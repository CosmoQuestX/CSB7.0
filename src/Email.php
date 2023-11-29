<?php

namespace CosmoQuestX;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email
{


    /**
     * @var string
     */
    private $to="";
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $body;

    public function __construct(string $to, string $subject, string $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function sendMail()
    {
        return self::sendEmail($this->to, $this->subject, $this->body);
    }

    public static function sendEmail(string $to, string $subject, string $body): bool
    {
        require_once "vendor/autoload.php";

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $emailSettings['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $emailSettings['username'];
            $mail->Password   = $emailSettings['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $emailSettings['port'];
            $mail->addAddress($to);
            $mail->setFrom($emailSettings['from'], 'Mailer');

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return TRUE;
        } catch (Exception $e) {
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
