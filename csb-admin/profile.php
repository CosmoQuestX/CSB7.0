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
require_once ("../csb-loader.php");
require_once ($DB_class);
require_once ($BASE_DIR."csb-admin/auth.php");
$adminFlag = 1;

/* ----------------------------------------------------------------------
   Check for post variables
   ---------------------------------------------------------------------- */
if(isset($_POST) && !empty($_POST)) {
    $login = FALSE;
    $reg = FALSE;
    if ($_POST['go'] == 'login') $login = TRUE;
    elseif ($_POST['go'] == 'reg') $reg = TRUE;
}

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);


if ($login || $user === FALSE ) { // NOT LOGGED IN
    require_once ($BASE_DIR."csb-content/templates/login.php");
}

/* ----------------------------------------------------------------------
    Are they trying to register?
   ---------------------------------------------------------------------- */

elseif ($reg) {
    require_once ($BASE_DIR."csb-content/templates/login.php");
}

/* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title;

    $page_title = "Profile & Settings";

    require_once($BASE_DIR . "/csb-content/template_functions.php");
    require_once("admin-dashboards.php");

    loadHeader();

/* ----------------------------------------------------------------------
    are they trying to save something they input?
   ---------------------------------------------------------------------- */

    if(isset($_GET)) {

        if (isset($_GET['email'])) {
            $query = "update users set email = ?";
            $params = array($_GET['email']);
            $params_type = "s";


            if (isset($_GET['first_name'])) {
                $query .= ", first_name = ?";
                $params [] = $_GET['first_name'];
                $params_type .="s";
            }

            if (isset($_GET['last_name'])) {
                $query .= ", last_name = ?";
                $params [] = $_GET['last_name'];
                $params_type .="s";
            }

            if (isset($_GET['public_name'])) {
                $query .= ", public_name = 1";
            } else {
                $query .= ", public_name = 0";
            }

            $query       .= " where id = ?";
            $params[]     = $_SESSION['user_id'];
            $params_type .= "s";

        } else {
            echo "email address required";
        }
        echo $db->update($query, $params_type, $params);
    }


// Go back to loading the page

    $thisUser = $db->getUser($_SESSION['user_id']);
    ?>

    <div id="main">
        <div class="container">

            <div id="" class="left-dash left">
                Things to do will go here
            </div>

            <div class="main-dash right">
                <img class="right" src="<?php echo $IMAGES_URL;?>Profile/Default_Avatar.png">
                <h3>
                    Welcome, <?php echo $user['name']; ?>
                </h3>
                <p>
                    <strong> Account Settings </strong><br/>
                <span class="instructions">Your privacy matters! Our team programmers do have access to this
                    information, but the only thing that can be publicly seen is your username. We will,
                    with permission only, use your first and last name to give you credit for things
                    you accomplish.</span></p>
                <form action="profile.php" method="get">
                <ul>
                    <li>Username: <?php echo $thisUser['name']; ?> </li>
                    <li>email: <input type="text" name="email" value="<?php echo $thisUser['email']; ?>"></li>
                    <li>change your password</li>
                    <li>First Name: <input type="text" name="first_name" value="<?php echo $thisUser['first_name']; ?>"></li>
                    <li>Last Name: <input type="text" name="last_name" value="<?php echo $thisUser['last_name']; ?>"></li>

                </ul>
                    <input type="checkbox" name="public_name"<?php if ($thisUser['public_name'] == 1) echo "checked"?>>
                    Do we have permission to publish your name with science results?
                    <input type="submit" value="Save Settings" class="btn-default right">
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>



    <?php
    loadFooter();
}

$db->closeDB();