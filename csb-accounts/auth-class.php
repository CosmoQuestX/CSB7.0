<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/3/19
 * Time: 9:41 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

class Auth
{
    function getUserByName($name)
    {
        $db_handle = new DB();
        $query = "Select * from users where name = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }
}