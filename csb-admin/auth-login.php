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
   require_once (__DIR__."/../csb-loader.php");
   require_once ("db_class.php");

/* ----------------------------------------------------------------------
   Logging out? Asking to register?
   ---------------------------------------------------------------------- */

    if(isset($_GET['go'])) {
        if ($_GET['go'] == 'logout') {
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
        elseif ($_GET['go'] == 'Register') {
            require_once ($BASE_DIR."csb-content/templates/register.php");

        }
        else {
            echo "get thee gone, URL hacking wizard";
        }
    }

    elseif (isset($_POST['go'])) {

        /* ----------------------------------------------------------------------
            Registering TODO add error checking: dup name, dup email
           ---------------------------------------------------------------------- */
        if ($_POST['go'] == 'register') {
            print_r($_POST);
            $db = new DB($db_servername, $db_username, $db_password, $db_name);
        }

        /* ----------------------------------------------------------------------
           Logging in? Check for post variables
---------------------------------------------------------------------- */

        elseif ($_POST['go'] == 'login') {
            // Does the user exist? Retrieve them from the db
            $db = new DB($db_servername, $db_username, $db_password, $db_name);
            $query = "SELECT * FROM users WHERE name = ? ";
            if ($user = $db->runQueryWhere($query, "s", array($_POST['name']))) {

                // Verify the password, set the cookie and session variable
                if (password_verify($_POST['password'], $user['password'])) {

                    // Set timeout variable & token for cookies based on remember checkbox
                    if (isset($_POST['remember']) && !strcmp($_POST['remember'], 'on')) {

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

                    if (isset($result['role_id'])) {
                        $roles = $result['role_id'];
                    } else {
                        $roles = "";
                        foreach ($result as $role) {
                            $roles .= $role['role_id'] . ",";
                        }
                    }

                    // Set sessions and cookie
                    $_SESSION['user_id'] = $user['id'];
                    setcookie('name', $_POST['name'], $timeout, "/");
                    $_SESSION['roles'] = $roles;
                    session_start();
                } else {
                    $db->closeDB();
                    die("wrong password"); //TODO load login.php with this as the error message
                }
                $db->closeDB();
            } else {
                die("user not found"); //TODO load login.php with this as the error message
            }

            // Send them where they belong TODO find a better way to do this
            ?>
            <html>
            <head>
                <meta http-equiv="Refresh" content="0; url=<?php echo("http://" . $_POST['referringURL']); ?>"/>
            </head>
            </html>
            <?php

        } else { // Javascript checks should prevent this from happening
            die("You don't belong here. Run away. Run away from the error.");
        }
    }

?>
