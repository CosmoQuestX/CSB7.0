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
require("profile-functions.php");
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

$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

global $user;
$user = isLoggedIn($db);


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
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title, $header_title, $SITE_TITLE;

    $page_title = $SITE_TITLE . "Profile & Settings";

    require_once($BASE_DIR . "csb-content/template_functions.php");

    loadHeader($page_title);

    /* ----------------------------------------------------------------------
        are they trying to save something they input? TODO : Add custom error messages like "No old password provided"
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
                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS));
                $params_type .= "s";
            }

            if (isset($_POST['last_name'])) {
                $query .= ", last_name = ?";
                $params[] = preg_replace("/;/", "", filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS));
                $params_type .= "s";
            }

            if (isset($_POST['public_name'])) {
                $query .= ", public_name = 1";
            } else {
                $query .= ", public_name = 0";
            }

            // Give the user the possibility to change the password, but don't overwrite with an empty password
            // Verify that old_password matches password currently in the database
            // Also, Javascript should prevent it, but make sure the password confirmation matches.
            if (isset($_POST['old_password']) && password_verify($_POST['old_password'], $curprofile['password']) && isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
                $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query .= ", password = ?";
                $params[] = $hashed;
                $params_type .= "s";
            }
            if (isset($_POST['avatar_service']) && $_POST['avatar_service'] !== "") {
                $query .= ", avatar_service = ?";
                $params[] = preg_replace("/;/", "", filter_var($_POST['avatar_service'], FILTER_VALIDATE_INT));
                $params_type .= "s";

                $query .= ", gravatar_url = ?";

                print_r($_POST['avatar_service']);
                $params[] = match ($_POST['avatar_service']) {// '0': Default; '1': Gravatar;
                    '1' => preg_replace("/;/", "", get_gravatar(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL))),
                    default => preg_replace("/;/", "", $BASE_URL . "csb-content/images/profile/Default_Avatar.png"),
                };

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
        $publicNameChecked = "checked";
    }
    else {
        $publicNameChecked = "";
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

            <label for='username'>Username</label>
            <input type='text' id='username' name='username' class='form-control' value='".$thisUser['name']."' disabled>

            <label for='email'>Email</label>
            <input type='text' id='email' name='email' class='form-control' value='".$thisUser['email']."'>

            <h3 class='font-weight-bold mt-4'>User Avatar</h3>

            <img src='".$thisUser['gravatar_url']."' height='100' width='100' alt='User Avatar'><br>" .
            ($DEBUG_MODE ? "<label for='gravatar-url'>Avatar URL [Debug]</label><input type='text' id='gravatar-url' name='gravatar_url' class='form-control' value='".$thisUser['gravatar_url']."' readonly>" : "") .
            "<label for='avatar-service'>Avatar Service</label>
            <select id='avatar-service' class='form-control' name='avatar_service'>
                <option value='0' ".($thisUser['avatar_service'] == 0 ? 'selected="selected"' : '').">Default</option>
                <option value='1'".($thisUser['avatar_service'] == 1 ? 'selected="selected"' : '').">Gravatar</option>
            </select>

            <h3 class='font-weight-bold mt-4'>Change Password</h3>

            <label for='old-pass'>Old Password</label>
            <input type='password' id='old-pass' name='old_password' class='form-control'>

            <label for='new-pass'>New Password</label>
            <input type='password' id='new-pass' name='password' class='form-control'>

            <label for='confirm-pass'>Confirm New Password</label>
            <input type='password' id='confirm-pass' name='confirm_password' class='form-control'>

            <input type='checkbox' name='public_name' class='mt-4' $publicNameChecked> Do we have permission to publish your name with science results?

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
        Your privacy matters! While our developers have access to your profile information,
        the only thing that can be publicly seen is your username. We will, with permission
        only, use your first and last name to give you credit for things you accomplish.
        </p>
        ";


    /* ----------------------------------------------------------------------
    Load the view
    ---------------------------------------------------------------------- */

    load3Col($menus, $main, $notes);
    loadFooter();


}


$db->closeDB();
