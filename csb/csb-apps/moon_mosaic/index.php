<?php


/* ----------------------------------------------------------------------
Get the settings and check if the person is logged in
---------------------------------------------------------------------- */

require_once("../../csb-loader.php");
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

$page_title = "Moon Mosaic";

// Make sure the page title is set
if (!isset($page_title)) { $page_title = $SITE_TITLE; };

require_once($BASE_DIR . "/csb-content/template_functions.php");

loadHeader($page_title);
require_once($THEME_DIR . "/home-template.php");
loadFooter();
// Only one header please
