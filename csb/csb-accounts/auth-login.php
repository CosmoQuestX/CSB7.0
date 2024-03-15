<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 10:34 PM
 */

/* ----------------------------------------------------------------------
   Start / renew the session
   ---------------------------------------------------------------------- */

session_start();

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */

require_once("../csb-loader.php");
require_once("db_class.php");
require("auth-login-functions.php");

/* ----------------------------------------------------------------------
   Logging out? Asking to register? Rescuing Password?
   ---------------------------------------------------------------------- */


$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

if (isset($_GET['go'])) {

    if ($_GET['go'] == 'logout') {
        logout($db);
    } else {
        echo "get thee gone, URL hacking wizard";
    }

} if (isset($_POST['go'])) {

    /* Logging in? Check for post variables ----------------------------- */
    if ($_POST['go'] == 'login') {
        $user=array();
        foreach ($_POST as $postkey=>$postvalue) {
            // We shouldn't need to except password, since you shouldn't be able
            // to sneak a password with a special char past the input filter.
            // But better err on the side of caution. The password is hashed
            // anyway.
            if ($postkey == 'password') {
                $user[$postkey]=$_POST[$postkey];
            }
            else if ($postkey == 'referringURL'){
                $user[$postkey]=filter_input(INPUT_POST, $postkey, FILTER_SANITIZE_URL);
            }
            else {
                $user[$postkey]=filter_input(INPUT_POST, $postkey, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }
        login($db, $user);

    /* Registering new user --------------------------------------------- */
    } elseif ($_POST['go'] == 'regForm') {

        // hash password
        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $error = "";
        // Check if name or email are in use, throw an error if it is
        if ($db->checkUser('name', filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
            $error .= "That username is taken. Try another.";
        }
        if ($db->checkUser('email', filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL))) {
            $error .= "That email is taken. Try another.";
        }

        if (!empty($error)) {
            $_SESSION['errMsg'] = "Error: " . $error;
            header("Location: " . $ACC_URL . "register.php");
        }
        // No errors? Kill the error
        else {
            regUser($db, $_POST, $hashed);
            // Send the newly registered user off to the main page instead of presenting a blank page.
            header("Location: " . $BASE_URL);
        }
        exit();

        /* Rescuing a Password ---------------------------------------------- */
    } elseif ($_POST['go'] == 'rescueForm') {
        $flag = TRUE;

        $name  = filter_input(INPUT_POST, 'nameORemail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'nameORemail', FILTER_SANITIZE_EMAIL);

        if ($db->checkUser('name', $name, 0)) {
            $_SESSION['errMsg'] = "found $name. ";
            rescueUser($db, "name", $name);
        } elseif ($db->checkUser('email', $email)) {
            $_SESSION['errMsg'] = "found $email. ";
            rescueUser($db, "email", $email);
        } else {
            $_SESSION['errMsg'] = "No username or email matched: $name";
            header("Location: " . $ACC_URL."/rescue.php");
            exit();
        }
    } elseif ($_POST['go'] == 'passwordReset') {
        die('Not Available'); // FIXME : See Trello for more info
        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "UPDATE users SET password ='".$hashed."'  WHERE email = '".$_POST['email']."'";
        $db->runQuery($query);
        header("Location: " . $ACC_URL."/rescue.php?go=success");
        exit();
    } else { // Javascript checks should prevent this from happening
        die("You don't belong here. Run away. Run away from the error.");
    }
}
$db->closeDB();
