<?php


header("Content-Type: application/json");

//Import the PHPMailer SMTP class into the global namespace
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// Build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));


//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

//Create a new SMTP instance
$smtp = new SMTP();

//Enable connection-level debug output
//$smtp->do_debug = SMTP::DEBUG_CONNECTION; // TODO : Remove Debug level

try {
    //Connect to an SMTP server [uses port 25 for manual telnet / only used in testing]
    if (!$smtp->connect($v->email_host, 25)) {
        echo json_encode(array('result' => false, 'message' => "Connection failed: " . $smtp->getError()['error'], 'code' => $smtp->getError()['smtp_code'] . " " . $smtp->getError()['smtp_code_ex']));
        //throw new Exception('Connect failed');
    }
    //Say hello
    if (!$smtp->hello(gethostname())) {
        echo json_encode(array('result' => false, 'message' => "EHLO failed: " . $smtp->getError()['error'], 'code' => $smtp->getError()['smtp_code'] . " " . $smtp->getError()['smtp_code_ex']));
        //throw new Exception('EHLO failed: ' . $smtp->getError()['error']);
    }
    //Get the list of ESMTP services the server offers
    $e = $smtp->getServerExtList();
    //If server can do TLS encryption, use it
    if (is_array($e) && array_key_exists('STARTTLS', $e)) {
        $tlsok = $smtp->startTLS();
        if (!$tlsok) {
            echo json_encode(array('result' => false, 'message' => "Encryption failed: " . $smtp->getError()['error'], 'code' => $smtp->getError()['smtp_code'] . " " . $smtp->getError()['smtp_code_ex']));
            //throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
        }
        //Repeat EHLO after STARTTLS
        if (!$smtp->hello(gethostname())) {
            echo json_encode(array('result' => false, 'message' => "EHLO (2) failed: " . $smtp->getError()['error'], 'code' => $smtp->getError()['smtp_code'] . " " . $smtp->getError()['smtp_code_ex']));
            //throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
        }
        //Get new capabilities list, which will usually now include AUTH if it didn't before
        $e = $smtp->getServerExtList();
    }
    //If server supports authentication, do it (even if no encryption)
    if (is_array($e) && array_key_exists('AUTH', $e)) {
        if ($smtp->authenticate($v->email_username, $v->email_password)) {
            echo json_encode(array('result' => true));
            //echo 'Connected ok!';
        } else {
            echo json_encode(array('result' => false, 'message' => "Authentication failed: " . $smtp->getError()['error'], 'code' => $smtp->getError()['smtp_code'] . " " . $smtp->getError()['smtp_code_ex']));
            //throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
        }
    }
} catch (Exception $e) {
//    echo 'SMTP error: ' . $e->getMessage(), "\n";
    echo json_encode(array('result' => false, 'message' => "SMTP error: " . $e->getMessage(), 'code' => null));
}
//Whatever happened, close the connection.
$smtp->quit();

exit();
