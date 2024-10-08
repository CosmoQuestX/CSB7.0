<?php

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
    $chkuser = $db->runQueryWhere($query, "s", array($user['username']))[0];

    if ($chkuser !== false ) {

        // Verify the password, set the cookie and session variable
        if (password_verify($user['password'], $chkuser['password'])) {

           $timeout = generateToken($db, $user, $chkuser['id']);

            // Get the person's roles
            $query = "SELECT role_id FROM role_users WHERE user_id = ?";
            $params = array($chkuser['id']);
            $result = $db->runQueryWhere($query, "i", $params);

            if ($result !== false) {
                foreach ($result as $role) {
                    $roles[]= $role['role_id'];

                }
            }

            // Insert the users' session information into the database
            insertUserSession($db, $chkuser['id']);

            // Get the person's tutorials completed
            $tcquery = "SELECT tutorials_completed FROM users WHERE id = ?";
            $tcparams = array($chkuser['id']);
            $tcresult = $db->runQueryWhere($tcquery, "i", $params)[0];

            if($tcresult === false){
                error_log("Query failed for tutorials_completed; SQL was: $tcquery with params=" . print_r($tcparams));
            }

            // Set sessions and cookie
            $_SESSION['user_id'] = $chkuser['id'];
            setcookie('name', $user['username'], $timeout, "/");
            setcookie('tutorials_complete', $tcresult['tutorials_completed'],$timeout,"/");
            $_SESSION['roles'] = $roles;

            // Send them where they belong
            header("Location: " . $user['referringURL']);
            exit();
        }
    }

    // If we've reached here, there's been an error

    $db->closeDB();
    $_SESSION['errmsg'] = "Login failed: Wrong username or password";
    // In case of error, exit not quite as gracefully.
    if (!isset($BASE_URL)) {
        die("Login Failed.");
    }

    // Send them where they belong
    header("Location: " . $user['referringURL']);
    exit();
}

/**
 * Function for logging out a user
 *
 * @return void
 */
function logout($db)
{
    global $BASE_URL;

    $timeout = time() - 3600;
    setcookie("token", "", $timeout, "/");
    setcookie('name', "", $timeout, "/");
    // Remove the session info from the database before clearing the session
    $session_query = "DELETE FROM sessions WHERE id = '" . $_COOKIE[ini_get('session.name')] . "'";
    $session_result = $db->runQuery($session_query);
    if ($session_result === false) {
        error_log("Error deleting session, query was: ". $session_query);
    }

    $_SESSION = array();
    session_destroy();
    session_start();

    // Send them where they belong
    header("Location: " . $BASE_URL);
    exit();
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
    global $CQ_ROLES;

    // Insert the user into the database
    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $params = array(filter_var($user['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0), filter_var($user['email'], FILTER_SANITIZE_EMAIL), $pwhash);
    $db->insert($query, "sss", $params);

    // Get the id for the freshly created user
    $query = "SELECT id FROM users WHERE name = '".$user['username']."'";
    $id = $db->runQuery($query)[0]['id'];

    if ($id === FALSE) {
        // This should not happen, since we just created the user!
        error_log("Could not find the freshly created user on registration.");
        die("Fatal error on registration. Please try again later.");
    } else {
        $user['id'] = $id;
    }

    // create their default role
    $default_role = $CQ_ROLES['SITE_USER'];

    $query = "INSERT INTO role_users (role_id, user_id) values (?, ?)";
    $params = array($default_role, $id);
    $role_insert=$db->insert($query, "ii", $params);
    if ($role_insert !== true) {
        error_log("Error adding role $roles for user $id");
    }

    // create sessions / cookies TODO Make this a function

    // Insert the users' session information into the database
    insertUserSession($db, $user['id']);

    $timeout = generateToken($db, $user, $user['id']);

    // Get the person's roles
    $query = "SELECT role_id, user_id FROM role_users WHERE user_id = ?";
    $params = array($user['id']);
    $result = $db->runQueryWhere($query, "s", $params);

    if ($result !== false) {
        foreach ($result as $role) {
            $roles[]= $role['role_id'];

        }
    }

    // Set sessions and cookie
    $_SESSION['user_id'] = $user['id'];
    setcookie('name', $user['username'], $timeout, "/");
    $_SESSION['roles'] = $roles;

}

/**
 * Set timeout variable & token for cookies based on remember checkbox
 * @param $db - The current database connection
 * @param $user - The user configuration for the registration attempt
 * @param $id - The user id from the database
 * @return int
 */
function generateToken ($db, $user, $id) {
    if (isset($user['remember']) && !strcmp($user['remember'], 'on')) {

        // How long will cookies last: 30 Days
        $timeout = time() + 60 * 60 * 24 * 30;

    } else {
        // How long will cookies last: 24min like sessions (set in php.ini)
        $timeout = time() + 60 * 60 * 24;
    }

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $token = substr(str_shuffle($chars), 0, 36);
    setcookie("token", $token, $timeout, "/");

    // update token in database to compare with later
    $token_hash = password_hash($token, PASSWORD_DEFAULT);
    $query = "UPDATE users SET remember_token = '" . $token_hash . "' WHERE id = ?";
    $params = array($id);
    $db->update($query, "s", $params);

    return $timeout;
}

function rescueUser ($db, $using, $value) {
    GLOBAL $emailSettings, $ACC_URL, $BASE_URL, $SITE_NAME, $BASE_DIR;

// Get the email to send information to
    if(strcmp($using, "email")==0) {
        $to = $value;
        $id = $db->getUserIdByEmail($value);
        $name = $db->getUser($id)['name'];
    } else {
        $id = $db->getUserIdByName($value);
        $to = $db->getUser($id)['email'];
        $name = $value;
    }

    // Set up the hash value to store
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $token = substr(str_shuffle($chars), 0, 12);
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    $query = "INSERT INTO password_resets (email, token) VALUES ('$to', '$hashedToken')";
    $db->runQuery($query);

    // Put the rescue link in a variable so we only have to calculate it once.
    $rescue_link = $ACC_URL."rescue.php?go=".$to."&token=".$token;

    $subject = $SITE_NAME." Password Reset";

    $msg =  "Hello, $name<br><br>".
        "Someone has requested a password reset for your account.<br><br>".
        "If you made this request and would like to reset your password, please go to <a href='$rescue_link'>this</a> link ($rescue_link).<br><br>".
        "Happy Science-ing! $SITE_NAME";

    require_once($BASE_DIR."csb-accounts/email_class.php");
    sendNewEmail($subject, $msg, $to, $emailSettings);

    // Everything worked so remove error msg and the rescue link
    unset($rescue_link);
    unset($_SESSION['errMsg']);
    header("Location: ".$ACC_URL."rescue.php?go=submitted");
    exit();
}

/**
 * Inserts the users' information into the session table of the database
 * @param resource $db A valid database connection
 * @param int $user The user id from the user
 * @return boolean True if successful, otherwise false
 */

function insertUserSession($db, $user) {

    /*
     * Since it can happen that a users' session gets garbage collected we need to have
     * an alternative way to determine which user tried to save something. So let's take
     * the information we have and write it into the database.
     * The information entered is:
     * - Session ID
     * - User ID
     * - IP Address the request originated from
     * - the User agent (e.g. Browser).
     * - the epoch of the last request.
     * There's another field in the database table that is called "payload"; I don't know
     * what it is good for, though. Since it does not accept null values, store the base64
     * encoded request string.
     */

    $ret = true;
    // Let's get this out of the way, we will use it anyway.
    $payload = base64_encode('http' . (($_SERVER['SERVER_PORT'] == 443) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

    // First, check if there is arlready a session for the session id
    // If there is, insert the session information into the table
    if ($db->runQuery("SELECT id FROM sessions WHERE id = '" . $_COOKIE[ini_get('session.name')]. "'") === false) {

        $session_query= "INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity) " .
            "VALUES (?, ?, ?, ?, ?, ?);";
        $session_param = array($_COOKIE[ini_get('session.name')], $user, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $payload, time());
        $session_result = $db->insert($session_query, "sisssi", $session_param);
        if ($session_result === false) {
            error_log("Query to insert session data failed; SQL was: ". $session_query);
            $ret=false;
        }
    }
    else {
        // We found a session with the same session id, so update it with the information.
        $session_query= "UPDATE sessions SET user_id = ?, ip_address = ?, user_agent = ?, payload = ?, last_activity = ? WHERE id = ?" ;
        $session_param = array($user, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $payload, time(), $_COOKIE[ini_get('session.name')]);
        $session_result = $db->update($session_query, "isssis", $session_param);
        if ($session_result === false) {
            error_log("Query to update session data failed; SQL was: ". $session_query);
            $ret=false;
        }
    }
    return $ret;
}

/**
 * Checks password strength
 */
function validate_password(string $password, string $confirm_password) {
    if(!empty($password) && ($password == $confirm_password)) {
        $password = test_input($password);
        $confirm_password = test_input($confirm_password);
        if (strlen($password) <= '8') {
            $passwordErr = "Your Password Must Contain At Least 8 Characters!";
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Number!";
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
        }
    }
    elseif(!empty($password)) {
        $cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
    } else {
        $passwordErr = "Please enter password   ";
    }
}
