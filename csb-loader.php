<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */

/* ----------------------------------------------------------------------
   Load things needed always
   ---------------------------------------------------------------------- */
global $BASE_DIR, $BASE_URL, $adminFlag;
if (stream_resolve_include_path("csb-settings.php") === false) { header ("Location: csb-installer/"); }
require "csb-settings.php";
$loader = TRUE;

/* ----------------------------------------------------------------------
   Define the theme

       1. Check if one is defined in the database  TODO
       2. Check if it is configured correctly       TODO
       3. If setup, use that theme, else use default    TODO
   ---------------------------------------------------------------------- */

// Default theme (if nothing set in database)
global $THEME_URL, $THEME_DIR, $SITE_TITLE;

$SITE_TITLE = $SITE_NAME." | ";

$THEME_DIR = $BASE_DIR . "csb-themes/default/";
$THEME_URL = $BASE_URL . "csb-themes/default/";

/* ----------------------------------------------------------------------
   Define other useful directories
   ---------------------------------------------------------------------- */

global $ADMIN_DIR, $DB_class, $email_class;

$ACC_URL = $BASE_URL . "csb-accounts/";
$ACC_DIR = $BASE_DIR . "csb-accounts/";
$ADMIN_URL = $BASE_URL . "csb-accounts/";
$ADMIN_DIR = $BASE_DIR . "csb-accounts/";
$DB_class = $ACC_DIR . "db_class.php";
$email_class = $ACC_DIR . "email_class.php";
$IMAGES_URL = $BASE_URL . "csb-content/images/";
$TEMPLATES_URL = $BASE_URL . "csb-content/templates/";
$TEMPLATES_DIR = $BASE_DIR . "csb-content/templates/";

/* ----------------------------------------------------------------------
   Setup User Roles - needed because of potential customizations
   ---------------------------------------------------------------------- */

include($DB_class);
$db = new DB($db_servername, $db_username, $db_password, $db_name);
$query = "SELECT * FROM roles";
$result = $db->runQuery($query);

foreach ($result as $row) {
    $CQ_ROLES[$row['name']] = $row['id'];
}
$db->closeDB();

$adminFlag = FALSE;








