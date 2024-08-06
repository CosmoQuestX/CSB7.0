<?php

/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */


/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

require_once("./csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

global $user;
$user = isLoggedIn($db);

/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

// Make sure the page title is set
if (!isset($page_title)) { $page_title = $SITE_TITLE; };

// Make sure the app e
if (isset($_GET['app'])) {
    $app = $_GET['app'];
    // check if the app is in the database and active
    $test = $db->getNumRows("applications", "WHERE name = '$app' AND active = 1");
    if ($test == 0) {
        header("Location: " . $BASE_URL . "error/error.php?error=404");
        exit();
    }
}
else {
    header("Location: " . $BASE_URL . "error/error.php?error=404");
    exit();
}

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader($page_title);
// application function loads here
require_once($BASE_DIR . "/csb-apps/" . $app . "/template.php");
loadFooter();
// Only one header please

