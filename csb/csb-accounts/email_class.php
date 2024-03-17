<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../../vendor/autoload.php";

/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 5:57 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader;
if (!isset($loader) && !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

//SETUP email admin
//SETUP email logged in user
//SETUP email specified address

// REMEMBER: Config for SMTP


class email
    /**
     * Class for email handling
     */
{
    private $host;
    private $username;
    private $password;
    private $encryption;
    private $port;
    private $from;

    /**
     * Initialization with the parameters from the settings
     *
     * @param array $params The email parameters from the settings
     */
    function __construct($params)
    {
        $this->host = filter_var($params['host'], FILTER_SANITIZE_URL);
        $this->username = $params['username'];
        $this->password = $params['password'];
        $this->encryption = filter_var($params['encryption']);
        $this->port = filter_var($params['port'], FILTER_SANITIZE_NUMBER_INT);
        $this->from = filter_var($params['from'], FILTER_SANITIZE_EMAIL);
    }

    /**
     * Send an email to a given email address
     *
     * @param string $to
     * @param array $msg an associative array with subject, body and onSuccess
     */
    function sendMail($to, $msg)
    {
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = $this->host;                 //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = $this->username;             //SMTP username
            $mail->Password = $this->password;             //SMTP password
            $mail->SMTPSecure = strtoupper($this->encryption) == "TLS" ?
                PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = $this->port;                 //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

            //Recipients
            $mail->setFrom($this->from, 'CSB7.0 Server');
            $mail->addAddress($to);

            //Content
            $mail->isHTML();                              //Set email format to HTML
            $mail->Subject = $msg['subject'];
            $mail->Body = $msg['body'];
            $mail->AltBody = $msg['alt-body'];

            $mail->send();
            return array('result' => true);
        } catch (Exception $e) {
            return array('result' => false, 'message' => 'An error has occurred');
        }

//        $headers = array(
//            'From' => $this->from,
//            'To' => filter_var($to, FILTER_SANITIZE_EMAIL),
//            'Subject' => $msg['subject'],
//            'Reply-To' => $this->from
//        );
//        $smtp = Mail::factory('smtp', array(
//            'host' => $this->host,
//            'port' => $this->port,
//            'auth' => true,
//            'username' => $this->username,
//            'password' => $this->password
//        ));
//        $mail = $smtp->send($to, $headers, $msg['body']);
//
//        if (PEAR::isError($mail)) {
//            error_log($mail->getMessage() . "/n");
//            die($mail->getMessage());
//        }
    }
}
