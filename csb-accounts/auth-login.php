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

/* ----------------------------------------------------------------------
   Logging out? Asking to register? Rescuing Password?
   ---------------------------------------------------------------------- */


$db = new DB($db_servername, $db_username, $db_password, $db_name);

if (isset($_GET['go'])) {

    if ($_GET['go'] == 'logout') {
        logout();
    } else {
        echo "get thee gone, URL hacking wizard";
    }

} if (isset($_POST['go'])) {

    /* Logging in? Check for post variables ----------------------------- */
    if ($_POST['go'] == 'login') {
        login($db, $_POST);

    /* Registering new user --------------------------------------------- */
    } elseif ($_POST['go'] == 'regForm') {

        // hash password & insert them into the database
        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($db->checkUser('name', filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
            $_SESSION['regmsg'] = "Cannot register, user name " . filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . " already exists!";
            header("Location:" . $BASE_URL."csb-accounts/register.php");
        } elseif ($db->checkUser('email', filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL))) {
            $_SESSION['regmsg'] = "Cannot register, email " . filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) . " already in use!";
            header("Location:" . $BASE_URL."csb-accounts/register.php");
        } else {
            regUser($db, $_POST, $hashed);
            // Send the newly registered user off to the main page instead of presenting a blank page.
            header("Location: " . $BASE_URL);
        }

    /* Rescuing a Password ---------------------------------------------- */
        /* Rescuing a Password ---------------------------------------------- */
    } elseif ($_POST['go'] == 'rescueForm') {
        $flag = TRUE;

        $name  = filter_input(INPUT_POST, 'nameORemail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'nameORemail', FILTER_SANITIZE_EMAIL);
        echo $name."-".$email;
        if ($db->checkUser('name', $name, 0)) {
            $_SESSION['errMsg'] = "found $name. ";
            die("STILL BEING IMPLEMENTED"); // will go to rescueUser("name", $name);
        } elseif ($db->checkUser('email', $email)) {
            $_SESSION['errMsg'] = "found $email. ";
            die("STILL BEING IMPLEMENTED"); // will go to rescueUser("email", $email);
        } else {
            $_SESSION['errMsg'] = "No username or email matched: $name";
            header("Location: " . $ACC_URL."/rescue.php");
        }

    } else { // Javascript checks should prevent this from happening
        die("You don't belong here. Run away. Run away from the error.");
    }
}
$db->closeDB();

/* ----------------------------------------------------------------------
   ALL NEEDED FUNCTIONS ARE BELOW
   ---------------------------------------------------------------------- */

/**
 * Function for logging in a user.
 *
 * @param object $db - the current database instance
 * @param array $user - the user configuration
 *
 * @return void
 */
function login($db, $user)
{

    global $BASE_URL;
    $query = "SELECT * FROM users WHERE name = ? ";

    if ($chkuser = $db->runQueryWhere($query, "s", array($user['name']))) {

        // Verify the password, set the cookie and session variable
        if (password_verify($user['password'], $chkuser['password'])) {

            // Set timeout variable & token for cookies based on remember checkbox
            if (isset($user['remember']) && !strcmp($user['remember'], 'on')) {

                // How long will cookies last: 30 Days
                $timeout = time() + 60 * 60 * 24 * 30;

                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
                $token = substr(str_shuffle($chars), 0, 36);
                setcookie("token", $token, $timeout, "/");

                // update token in database to compare with later
                $token_hash = password_hash($token, PASSWORD_DEFAULT);
                $query = "UPDATE users SET remember_token = '" . $token_hash . "' WHERE id = ?";
                $params = array($user['id']);
                $db->runQueryWhere($query, "s", $params);

            } else {
                // How long will cookies last: 24min like sessions (set in php.ini)
                $timeout = time() + 60 * 24;
            }

            // Get the person's roles
            $query = "SELECT role_id, user_id FROM role_users WHERE user_id = ?";
            $params = array($chkuser['id']);
            $result = $db->runQueryWhere($query, "s", $params);

            if (isset($result['role_id'])) {
                $roles = $result['role_id'];
            } else {
                $roles = "";
                foreach ($result as $role) {
                    $roles .= $role['role_id'] . ",";
                }
            }

            // Set sessions and cookie
            $_SESSION['user_id'] = $chkuser['id'];
            setcookie('name', $user['name'], $timeout, "/");
            $_SESSION['roles'] = $roles;
            session_start();

        } else {

            $db->closeDB();
            $_SESSION['errmsg'] = "Login failed: Wrong password";
            // In case of error, exit not quite as gracefully.
            if (!isset($BASE_URL)) {
                die("wrong password");
            }
        }

    } else {

        $db->closeDB();
        $_SESSION['errmsg'] = "Login failed: User " . $user['name'] . " not found";
        // In case of error, exit not quite as gracefully.
        if (!isset($BASE_URL)) {
            die("user not found");
        }
    }

    // Send them where they belong 
    header("Location: " . $user['referringURL']);

}

/**
 * Function for logging out a user
 *
 * @return void
 */
function logout()
{
    global $BASE_URL;

    $timeout = time() - 3600;
    setcookie("token", "", $timeout, "/");
    setcookie('name', "", $timeout, "/");
    $_SESSION = array();
    session_destroy();
    session_start();

    ?>
    <html>
    <head>
        <meta http-equiv="Refresh" content="0; url=<?php echo($BASE_URL); ?>"/>
    </head>
    </html>
    <?php

}

/**
 * Register a new user
 *
 * @param resource $db - the current database connection
 * @param array $user - the user configuration for the registration attempt
 * @param string $pwhash - a hash of the password the new user entered
 */
function regUser($db, $user, $pwhash)
{
    global $CQ_ROLE;

    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $params = array(filter_var($user['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0), filter_var($user['email'], FILTER_SANITIZE_EMAIL), $pwhash);
    $db->insert($query, "sss", $params);

    $query = "SELECT id FROM users WHERE name = '".$user['name']."'";
    //$query = "SELECT id FROM users WHERE name = 'test25'";

    $id = $db->runBaseQuery($query)[0]['id'];

    if ($id === FALSE) {
        echo "if";
        // This should not happen, since we just inserted a user 
        error_log("Could not find the freshly created user on registration.");
        die("Fatal error on registration. Please try again later.");
    } else {
        echo "else";
        $user['id'] = $id;
    }

    // create their default role
    $roles = $CQ_ROLES['SITE_NONE'];

    $query = "INSERT INTO role_users (role_id, user_id) values (?, ?)";
    $params = array($roles, $id);
    $db->insert($query, "ii", $params);

    // create sessions / cookies TODO Make this a function

    // Set timeout variable & token for cookies based on remember checkbox
    if (isset($user['remember']) && !strcmp($user['remember'], 'on')) {

        // How long will cookies last: 30 Days
        $timeout = time() + 60 * 60 * 24 * 30;

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $token = substr(str_shuffle($chars), 0, 36);
        setcookie("token", $token, $timeout, "/");

        // update token in database to compare with later
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $query = "UPDATE users SET remember_token = '" . $token_hash . "' WHERE id = ?";
        $params = array($user['id']);
        $db->runQueryWhere($query, "s", $params);
    } else {
        // How long will cookies last: 24min like sessions (set in php.ini)
        $timeout = time() + 60 * 24;
    }

    // Get the person's roles
    $query = "SELECT role_id, user_id FROM role_users WHERE user_id = ?";
    $params = array($user['id']);
    $result = $db->runQueryWhere($query, "s", $params);

    // Set sessions and cookie
    $_SESSION['user_id'] = $user['id'];
    setcookie('name', $user['name'], $timeout, "/");
    $_SESSION['roles'] = $roles;


}

function rescueUser ($db, $form) {
    GLOBAL $emailSettings;


    //Check if name is empty, if not check if user exists if
    //Check if email is empty, if not check if email exists
    //Have a user? Email and confirm


    require_once("email_class.php");

    $email = new EMAIL($emailSettings);

    $to = "starstryder@gmail.com";
    $msg['subject'] = "CosmoQuest Password Reset";
    $msg['body'] = "Someone has requested a ";

    $email->sendMail($to, $msg);

    die("here");

    //No one? send error
}

?>
