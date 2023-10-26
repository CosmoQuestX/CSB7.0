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


/**
 * Compare the user name against the database for a given id
 *
 * @param resource $db
 * @param int $id
 * @param string $name
 * @return boolean
 */

function chk_UserId($db, $id, $name) : bool
{

    $query = "SELECT id, name FROM users WHERE id = ?";
    $params = array($id);
    $result = $db->runQueryWhere($query, "s", $params)[0];

    // strip out any white space and make everything lower case because typing
    $comp = strtolower(trim($result['name'], "\t\n\r\0\x0B"));
    $name = strtolower(trim($name, " \t\n\r\0\x0B"));


    if (!strcmp($comp, $name))
        return TRUE;
    else
        return FALSE;

}

/**
 * Compare the "remember me" cookie against the database for a given token
 *
 * @param resource $db
 * @param string $token
 * @param string $name
 * @return boolean
 */
function chk_Token($db, $token, $name)
{

    $query = "SELECT id, name, remember_token FROM users WHERE name = ?";
    $params = array($name);
    $result = $db->runQueryWhere($query, "s", $params)[0];

    if (password_verify($token, $result['remember_token'])) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * Function: isLoggedIn
 * Purpose: Check session/cookies to see if logged in or remembered
 *
 * @param resource $db - the current database connection
 * @return mixed an array with user name and session id when logged int
 *    or false if the user isn't currently logged in
 *
 */

function isLoggedIn($db)
{

    $flag = FALSE;

    // look for the session id to be valid
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && !empty($_COOKIE["name"])) {
        $flag = chk_UserId($db, $_SESSION['user_id'], $_COOKIE["name"]);
    } // see if the cookie - WHICH CAN BE TAMPERED WITH - matches the DB
    elseif (!empty($_COOKIE["name"]) && !empty($_COOKIE["token"])) {
        $flag = chk_Token($db, $_COOKIE["token"], $_COOKIE["name"]);
    }


    if ($flag !== FALSE) {
        return array("name" => $_COOKIE["name"], "id" => session_id());
    } else
        return FALSE;
}

/**
 * Checks if the user is assigned to one of the roles supplied as an argument
 * @param string ...$roles Any number of roles to check
 * @return boolean true if at least one match is found, false if not
 */
function userHasRole(...$roles)
{
    // If it's called without roles, ensure that $roles is an array
    if(!is_array($roles)) { $roles = array(); }
    // If _SOMEHOW_ users end up without roles, make sure it's at least an array.
    if(!is_array($_SESSION['roles'])) { $_SESSION['roles'] = array(); }

    if (count(array_intersect($roles ,$_SESSION['roles'])) > 0) {
        return true;
    }
    else {
        return false;
    }

}
