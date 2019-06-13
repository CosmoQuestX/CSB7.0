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

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */

    require_once("auth-class.php");
    require_once("db_class.php");
    $db = new DB($db_servername, $db_username, $db_password, $db_name);

/**
 * Function: isLoggedIn
 * Purpose: Check session/cookies to see if logged in or remembered
 *
 * @return bool
 *
 */
function isLoggedIn() {

    $flag = FALSE;
    // look for the session id to be valid
     if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && !empty($_COOKIE["name"]) ) {
         $flag = TRUE;
     }

     // see if the cookie - WHICH CAN BE TAMPERED WITH - matches the DB
     elseif (!empty($_COOKIE["name"]) && !empty($_COOKIE["token"])) {
         $flag = TRUE;
     }

     if($flag) {
         $user = array( "name" => $_COOKIE["name"], "id" => session_id() );
         return $user;
     }
     else
         return FALSE;
}

