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
require_once($BASE_DIR . "csb-account/auth.php");
$adminFlag = 1;

/* ----------------------------------------------------------------------
   Check for post variables
   ---------------------------------------------------------------------- */
if (isset($_POST) && !empty($_POST)) {
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


if ($login || $user === FALSE) { // NOT LOGGED IN
    echo "Login Required"; // TODO open login alert
} /* ----------------------------------------------------------------------
   Do they have the correct role?
   ---------------------------------------------------------------------- */

elseif ($_SESSION['roles'] != $CQ_ROLES['SITE_SUPERADMIN'] || $_SESSION['roles'] != $CQ_ROLES['SITE_ADMIN']) {
    die("ERROR: You don't have permission to be here");
} /* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title;

    $page_title = "CSB Administration Dashboards";

    require_once($BASE_DIR . "/csb-content/template_functions.php");
    require_once("admin-dashboards.php");

    loadHeader();
    ?>

    <div id="main">
        <div class="container">

            <div id="" class="left-dash left">
                <?php listDashboards(); ?>
            </div>

            <div class="main-dash right">

                <h3>
                    Current Status
                </h3>
                <p>
                    Stuff will go here
                </p>

                <h3>
                    Current Stats
                </h3>
                <p>
                    Stats will go here
                </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>


    <?php
    loadFooter();
}

$db->closeDB();