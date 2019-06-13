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
   require_once ("../csb-loader.php");
   require_once ("db_class.php");
   $db = new DB($db_servername, $db_username, $db_password, $db_name);


/* ----------------------------------------------------------------------
   Check for get variables - If set, see if person can be logged in
   ---------------------------------------------------------------------- */

    if (isset($_POST)) {

        //print_r($_POST);

        // Does the user exist? Retrieve them from the db
        $query  = "SELECT * FROM users WHERE name = ? ";
        if ($user = $db->runQueryWhere($query, "s", array("Codeherder"))) {

            // Verify the password
            if(password_verify($_POST['password'], $user['password'])) {

                // Set the cookie and session variable

                // Set timeout variable & token for cookies based on remember checkbox
                if(isset($_POST['remember']) && !strcmp($_POST['remember'], 'on')) {
                    $timeout = time() + 60*60*24*30; // 30 days

                    $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
                    $token    = substr( str_shuffle( $chars ), 0, 36);
                    setcookie("token", $token, time() + (24*60*60)*30, "/");

                    // update token in database to compare with later
                    $token_hash = password_hash($token, PASSWORD_DEFAULT);
                    $query = "UPDATE users SET remember_token = '".$token_hash."' WHERE id = ?";
                    $params = array ($user['id']);
                    $db->runQueryWhere($query, "s", $params);

                }
                else {
                    $timeout = time() + 60*24; // 24 minutes
                }

                // Get user role and set
                //$query = "SELECT * FROM roles_user";

                // Set sessions and cookie
                $_SESSION['user_id'] = $user['id'];
                setcookie('name', $_POST['name'], $timeout, "/");


            }
            else {
                die("wrong password");
            }
        }
        else {
            die("user not found");
        }

        // Send them where they belong TODO find a better way to do this
        ?>
        <html>
            <head>
                <meta http-equiv="Refresh" content="0; url=<?php echo("http://".$_POST['referringURL']);?>" />
            </head>
        </html>
        <?php

    } else { // Javascript checks should prevent this from happening
        die("You don't belong here. Run away. Run away from the error.");
    }

?>
