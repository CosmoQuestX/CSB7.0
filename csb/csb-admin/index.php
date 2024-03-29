<?php

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
global $login;
require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");
$adminFlag = 1; // This is an admin page, so set the flag to 1

global $user, $BASE_URL, $CQ_ROLES;

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);


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
    Are they an Admin?
    --------------------------------------------------------------------- */

elseif (!$admin) {
    header('Location: ' . $BASE_URL); // If they are not an admin, send them to the home page
}  /* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title, $header_title, $SITE_TITLE;

    $page_title = $SITE_TITLE . "Admin Settings";

    require_once($BASE_DIR . "csb-content/template_functions.php");

    loadHeader($page_title);

    // Set variables to populate the template
    $menus = "<h4>Menus</h4>";
    $main = "<h4>Dashboard: ";
    $notes = "<h4>Instructions</h4>";

    /* ----------------------------------------------------------------------
        are they trying to save something they input?
       ---------------------------------------------------------------------- */
    if (isset($_GET) && !empty($_GET)) {
        if(isset($_GET['option'])) {
            $option = $_GET['option'];
            $main .= ucfirst($option)."</H4>";
            require_once($BASE_DIR . "csb-admin/dashboards/$option/$option.php");
            if (isset($_POST['action']) && !empty($_POST['action'])) {
               $text = doSwitch($db, $_POST['action']);
            } else {
                $text = landing($db);
            }
            $main .= $text['main'];
            $notes .= $text['notes'];
        } else {
            $main .= "NONE!</h4><p>ERROR: No Option Selected. Did you try URL hacking?</p>";
        }

    } else {
        // Display Key Information
        $main .= "Overview</h4><p>Need to add stuff</p>";

    }

    /* ----------------------------------------------------------------------
        are they trying to save something they input?
       ---------------------------------------------------------------------- */

    // Something here

/* ----------------------------------------------------------------------
   Setup Menus
   ---------------------------------------------------------------------- */

    $menus = "<h4>Menus</h4>";
    //get the names of the directories in the dashboards folder
    $dashboards = scandir($BASE_DIR . "csb-admin/dashboards");

    foreach ($dashboards as $dashboard) {
        if ($dashboard != "." && $dashboard != "..") {
            //Format the dashboard name to be human readable
            $dashboardName = str_replace("-", " ", $dashboard);
            //Make the first element of the dashboard name uppercase
            $dashboardName = ucfirst($dashboardName);
            $menus .= "<a href='./index.php?option=$dashboard'>$dashboardName </a><br>";
        }
    }

/* ----------------------------------------------------------------------
    Create the page
   ---------------------------------------------------------------------- */

    load3Col($menus, $main, $notes);
    loadFooter();


}

$db->closeDB();
