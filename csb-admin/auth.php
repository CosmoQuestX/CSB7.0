<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/3/19
 * Time: 9:41 PM
 */

/* ----------------------------------------------------------------------
   Start / renew the session
   ---------------------------------------------------------------------- */

    session_start();



function chk_UserId($db, $id, $name) {

    $query  = "SELECT id, name FROM users WHERE id = ?";
    $params = array($id);
    $result = $db->runQueryWhere($query, "s", $params);


    // strip out any white space and make everything lower case because typing
    $comp = strtolower(trim ( $result['name'], "\t\n\r\0\x0B"));
    $name = strtolower(trim ( $name, " \t\n\r\0\x0B"));


    if (!strcmp($comp, $name))
        return TRUE;
    else
        return FALSE;

}

function chk_Token($db, $token, $name) {

    $query  = "SELECT id, name, remember_token FROM users WHERE name = ?";
    $params = array($name);
    $result = $db->runQueryWhere($query, "s", $params);

    if (password_verify($token, $result['remember_token'])) {
        return TRUE;
    }
    else
        return FALSE;

}

/**
 * Function: isLoggedIn
 * Purpose: Check session/cookies to see if logged in or remembered
 *
 * @return bool
 *
 */

function isLoggedIn($db) {

    // look for the session id to be valid
     if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && !empty($_COOKIE["name"]) ) {
         $flag = chk_UserId($db, $_SESSION['user_id'], $_COOKIE["name"]);
     }

     // see if the cookie - WHICH CAN BE TAMPERED WITH - matches the DB
     elseif (!empty($_COOKIE["name"]) && !empty($_COOKIE["token"])) {
         die("there");
         $flag = chk_Token($db, $_COOKIE["token"], $_COOKIE["name"]);
     }

     if($flag) {
         $user = array( "name" => $_COOKIE["name"], "id" => session_id() );
         return $user;
     }
     else
         return FALSE;
}

