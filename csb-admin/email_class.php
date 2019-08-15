<?php

/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 5:57 PM
 */


// TODO: ADD ERROR CHECKING SO NO LITTLE JOHNNY TABLES FAIL

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

//TODO

//SETUP email admin
//SETUP email logged in user
//SETUP email specified address

// REMEMBER: Config for SMTP



class email
{
    private $host;
    private $username;
    private $password;
    private $port;
    private $from;

    function __construct($params) {
        $this->host     = $params['host'];
        $this->username = $params['username'];
        $this->password = $params['password'];
        $this->port     = $params['port'];
        $this->from     = $params['from'];

    }


    /**
     * @param $to
     * @param $msg = array(subject, body, onSuccess)
     */
    function sendMail($to, $msg) {
        $headers = array(
            'From'      => $this->from,
            'To'        => $to,
            'Subject'   => $msg['subject'],
            'Reply-To'  => $this->from
        );
        $smtp = Mail::factory('smtp', array (
            'host' => $this->host,
            'port' => $this->port,
            'auth' => true,
            'username' => $this->username,
            'password' => $this->password
        ));
        $mail = $smtp->send($to, $headers, $msg['body']);

        if (PEAR::isError($mail)) {
            error_log($mail->getMessage() . "/n");
        }
    }
}
