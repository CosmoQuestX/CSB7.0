<?php
/* ----------------------------------------------------------------------
 Get the settings and check if the person is logged in
 ---------------------------------------------------------------------- */

require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

/* ----------------------------------------------------------------------
 Is the person logged in?
 ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user, $ACC_URL;
$user = isLoggedIn($db);

if ($user !== false) {
    // When the user is logged in, he does not need to log in.
    // Redirect him to the main page.
    header("Location: " . $BASE_URL);
    exit();
}


/* ----------------------------------------------------------------------
 Load content
 ---------------------------------------------------------------------- */

// This is admittedly not the most elegant solution since it displays an
// almost empty page but it is better than throwing an error. 
// TODO: Add a better way to prompt the user to login after session timeout, preferrably not showing them an empty page.

$menus = "";
$main  = "You have been logged out. Please log in again using the Login button.";
$notes = "";


/* ----------------------------------------------------------------------
 Load the view
 ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

$page_title = $SITE_TITLE . "Login";

$_SESSION['showmodal'] = true;

loadHeader($page_title);
load3Col($menus, $main, $notes, 'mustLogin-template.php');
loadFooter();

?>