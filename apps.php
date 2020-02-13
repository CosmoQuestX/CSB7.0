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

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);

if (!$user) {
    header("Location: $BASE_URL");
}

/* ----------------------------------------------------------------------
   Check if app query parameter was provided and points to an app
   ---------------------------------------------------------------------- */
   // TODO if projects are made to be dynamic as opposed to directories, this will need refactored
if (!isset($_GET) || !isset($_GET['app']) || !is_dir(realpath($BASE_DIR . 'csb-apps/' . $_GET['app']))) {
    // TODO this could probably redirect to a 404 page
    header("Location: $BASE_URL");
}

$app = $_GET['app'];

/** Get the setup files for the app dynamically */
require_once($BASE_DIR . "/csb-apps//" . $app .  "/template.php");
$lang = $BASE_DIR . "csb-apps//" . $app . "/lang/en.json";

$lang = file_get_contents($lang);
$lang = json_decode($lang, true);

/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

loadHeader($page_title);
require_once($THEME_DIR . "/app-template.php");
loadFooter();