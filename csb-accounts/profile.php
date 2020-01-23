<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");
$adminFlag = 1;

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

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);


if ($login || $user === FALSE) { // NOT LOGGED IN
    require_once($BASE_DIR . "csb-content/templates/login.php");
} /* ----------------------------------------------------------------------
    Are they trying to register?
   ---------------------------------------------------------------------- */

elseif ($reg) {
    require_once($BASE_DIR . "csb-content/templates/login.php");
} /* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title;

    $page_title = "Profile & Settings";

    require_once($BASE_DIR . "csb-content/template_functions.php");
    require_once($BASE_DIR . "csb-admin/admin-dashboards.php");

    loadHeader($page_title);

    /* ----------------------------------------------------------------------
        are they trying to save something they input?
       ---------------------------------------------------------------------- */

    if (isset($_POST) && !empty($_POST)) {
        // Fetch old data to compare. 
        $curprofile = $db->getUser($_SESSION['user_id']);

        // Save email only when not empty, otherwise use the current one
        if (isset($_POST['email'])) {
            $query = "update users set email = ?";
            $params[] = $_POST['email'] != "" ? filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) : $curprofile['email'];
            $params_type = "s";

            if (isset($_POST['first_name'])) {
                $query .= ", first_name = ?";
                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                $params_type .= "s";
            }

            if (isset($_POST['last_name'])) {
                $query .= ", last_name = ?";
                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                $params_type .= "s";
            }

            if (isset($_POST['public_name'])) {
                $query .= ", public_name = 1";
            } else {
                $query .= ", public_name = 0";
            }
            // Give the user the possibility to change the password, but don't overwrite with an empty password
            // Also, Javascript should prevent it, but make sure the password confirmation matches.
            if (isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
                $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query .= ", password = ?";
                $params[] = $hashed;
                $params_type .= "s";
            }

            $query .= " where id = ?";
            $params[] = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
            $params_type .= "s";

        } else {
            echo "Email address required";
            // Make sure this doesn't get saved, if somehow Email isn't set
            $query = "";
        }
        if ($db->update($query, $params_type, $params)) {
            $saved = TRUE;
        } else {
            $saved = FALSE;
        }
    }


    /* ----------------------------------------------------------------------
        Create the page
       ---------------------------------------------------------------------- */

    $menus = "Put Menus Here";
    $main = "main";
    $notes = "Put Instructions Here";



    // Check whether to check the permission checkbox
    $thisUser = $db->getUser(filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT));

    if ($thisUser['public_name'] == 1) {
        $checked = "checked";
    }
    else {
        $checked = "";
    }

    // Create Registration Form
    $main = "
        <h3 class='font-weight-bold'>Welcome, " . $user['name'] . "!</h3>
        <form id='profile-form' action='".$_SERVER['REQUEST_URI']."' method='POST' onSubmit='checkPasswd(this);'>
            
            <div class='row'>
                <div class='col'>
                    <label for='first-name'>First Name</label>
                    <input type='text' id='first-name' name='first_name' class='form-control' value='".$thisUser['first_name']."'>
                </div>
                <div class='col'>
                    <label for='last-name'>Last Name</label>
                    <input type='text' id='last-name' name='last_name' class='form-control' value='".$thisUser['last_name']."'>
                </div>
            </div>
            
            <label for='email'>Email</label>
            <input type='text' id='email' name='email' class='form-control' value='".$thisUser['email']."'>

            <h3 class='font-weight-bold mt-4'>Change Password</h3>
            
            <label for='new-pass'>New Password</label>
            <input type='password' id='new-pass' name='password' class='form-control'>
            
            <label for='confirm-pass'>Confirm New Password</label>
            <input type='password' id='confirm-pass' name='confirm_password' class='form-control'>

            <input type='checkbox' name='public_name' class='mt-4' $checked> Do we have permission to publish your name with science results?

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
        <h5 class='font-weight-bold'>How we use your information</h5>
        <p>
        Your privacy matters! Our team programmers do have access to this
        information, but the only thing that can be publicly seen is your username. We will,
        with permission only, use your first and last name to give you credit for things
        you accomplish.
        </p>
        ";


    /* ----------------------------------------------------------------------
    Load the view
    ---------------------------------------------------------------------- */
    global $page_title, $header_title, $SITE_TITLE;

    require_once($BASE_DIR . "/csb-content/template_functions.php");

    $page_title = $SITE_TITLE . "Registration";

    //TODO Set error for if loading while logged in

    loadHeader($page_title);
    load3Col($menus, $main, $notes);
    loadFooter();
    

    ?>
    <script src='<?php echo $BASE_URL . "csb-content/js/profile.js" ?>'></script>
    <?php

}






$db->closeDB();