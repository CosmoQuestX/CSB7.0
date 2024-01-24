<?php

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");
$adminFlag = 1; // TODO : Does this do anything?

/* ----------------------------------------------------------------------
   Check for post variables
   ---------------------------------------------------------------------- */
$login = FALSE;
$reg = FALSE;
if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['go']) && $_POST['go'] == 'login') $login = TRUE;
    elseif (isset($_POST['go']) && $_POST['go'] == 'reg') $reg = TRUE;
}

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

global $user;
$user = isLoggedIn($db);
$admin = userHasRole($CQ_ROLES['SITE_ADMIN'], $CQ_ROLES['SITE_SUPERADMIN']);

if ($login || $user === FALSE) { // NOT LOGGED IN

    /* it would probably good to output some error like, session timeout, do
     * you want to log in again, but if the session does time out, it is
     * likely a lot better to just send them to the login than to let them
     * run into an error message...
     */
    header('Location: ' . $BASE_URL . 'csb-accounts/login.php');

} /* ----------------------------------------------------------------------
    Are they trying to register?
   ---------------------------------------------------------------------- */

elseif ($reg) {
    // I don't know how they got there, but send them to the registration page
    header('Location: ' . $BASE_URL . 'csb-accounts/register.php');
} /* ----------------------------------------------------------------------
    Are they an Admin?
    --------------------------------------------------------------------- */

elseif (!$admin) {
    header('Location: ' . $BASE_URL); // TODO : Make 403 Forbidden show up
}  /* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title, $header_title, $SITE_TITLE;

    $page_title = $SITE_TITLE . "Admin Settings";

    require_once($BASE_DIR . "csb-content/template_functions.php");

    loadHeader($page_title);

    /* ----------------------------------------------------------------------
        are they trying to save something they input?
       ---------------------------------------------------------------------- */
    if (isset($_POST) && !empty($_POST)) {

        // Fetch old data to compare.
        $query = "SELECT * FROM options";
        $result = $db->runQuery($query);

        $changed = FALSE;

        // Parse options into key/value pairs
        foreach ($result as $row) {
            $options[$row['option_name']] = $row['option_value'];
        }

        $query = "";

        $tempDebugMode = $_POST['debug_mode'] == "on" ? "1" : "0";

        if ($tempDebugMode != $options['debug_mode']) {
            $changed = TRUE;
            $query .= "update options set option_value = ? where option_name = 'debug_mode';";
            $params_type = "s";
            $params[] = $tempDebugMode;
        }

        if ($changed) {
            if ($db->update($query, $params_type, $params)) {
                $saved = TRUE;
            } else {
                $saved = FALSE;
            }
        }

    }

    /* ----------------------------------------------------------------------
        are they trying to save something they input?
       ---------------------------------------------------------------------- */

//    if (isset($_POST) && !empty($_POST)) {
//        // Fetch old data to compare.
//        $curprofile = $db->getUser($_SESSION['user_id']);
//
//        // Save email only when not empty, otherwise use the current one
//        if (isset($_POST['email'])) {
//            $query = "update users set email = ?";
//            $params[] = $_POST['email'] != "" ? filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) : $curprofile['email'];
//            $params_type = "s";
//
//            if (isset($_POST['first_name'])) {
//                $query .= ", first_name = ?";
//                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS));
//                $params_type .= "s";
//            }
//
//            if (isset($_POST['last_name'])) {
//                $query .= ", last_name = ?";
//                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS));
//                $params_type .= "s";
//            }
//
//            if (isset($_POST['public_name'])) {
//                $query .= ", public_name = 1";
//            } else {
//                $query .= ", public_name = 0";
//            }
//            // Give the user the possibility to change the password, but don't overwrite with an empty password
//            // Also, Javascript should prevent it, but make sure the password confirmation matches.
//            if (isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
//                $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
//                $query .= ", password = ?";
//                $params[] = $hashed;
//                $params_type .= "s";
//            }
//            if (isset($_POST['avatar_service']) && $_POST['avatar_service'] !== "") {
//                $query .= ", avatar_service = ?";
//                $params[] = preg_replace("/;/", "", filter_var($_POST['avatar_service'], FILTER_VALIDATE_INT));
//                $params_type .= "s";
//
//                $query .= ", gravatar_url = ?";
//                if ($_POST['avatar_service'] == '1') { // if Gravatar selected, generate avatar, else use default
//                    $params[] = preg_replace("/;/", "", get_gravatar($_POST['email']));
//                } else {
//                    $params[] = preg_replace("/;/", "", $BASE_URL."csb-content/images/profile/Default_Avatar.png"); // FIXME : The Base URL should not need to be defined, what if CodeHerder wants to change their domain?
//                }
//                $params_type .= "s";
//            }
//
//            $query .= " where id = ?";
//            $params[] = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
//            $params_type .= "s";
//
//        } else {
//            echo "Email address required";
//            // Make sure this doesn't get saved, if somehow Email isn't set
//            $query = "";
//        }
//        if ($db->update($query, $params_type, $params)) {
//            $saved = TRUE;
//        } else {
//            $saved = FALSE;
//        }
//    }


    /* ----------------------------------------------------------------------
        Create the page
       ---------------------------------------------------------------------- */

    $menus = "Put Menus Here";
    $main = "main";
    $notes = "Put Instructions Here";

    // Keep the duplicated code to check for form changes made above
    // Request options table
    $query = "SELECT * FROM options";
    $result = $db->runQuery($query);

    // Parse options into key/value pairs
    foreach ($result as $row) {
        $options[$row['option_name']] = $row['option_value'];
    }

    // Check whether to check the debug mode checkbox
    if ($options['debug_mode'] == 1) {
        $debugModeChecked = "checked";
    }
    else {
        $debugModeChecked = "";
    }

    // Create Registration Form
    $main = "
        <h3 class='font-weight-bold'>Admin Settings</h3>
        <form id='profile-form' action='".$_SERVER['REQUEST_URI']."' method='POST'>
            <input type='hidden' name='debug_mode' value='0'>
            <label for='debug_mode'>Debug Mode:</label>
            <input type='checkbox' name='debug_mode' id='debug_mode' class='mt-4' $debugModeChecked>

            <input type='submit' value='Save Settings' class='btn btn-cq mt-4 right'>
        </form>
        ";

    if (isset($saved) && $saved) {
        $main .= "<div class='text-success'>Settings saved!</div>";
        unset($saved);
    }
    elseif (isset($saved) && !$saved) {
        $main .= "<div class='text-danger'>Error saving settings!</div>";
        unset($saved);
    }

    $notes = "
        <h5 class='font-weight-bold'>Some Title Here</h5>
        <p>
        This should contain important info at some point.
        </p>
        ";


    /* ----------------------------------------------------------------------
    Load the view
    ---------------------------------------------------------------------- */

    load3Col($menus, $main, $notes);
    loadFooter();


}

$db->closeDB();
