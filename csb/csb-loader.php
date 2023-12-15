<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */

/* ----------------------------------------------------------------------
   Turn on Debugger - TEMPORARY
   ---------------------------------------------------------------------- */
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');


/* ----------------------------------------------------------------------
   Load things needed always
   ---------------------------------------------------------------------- */
global $BASE_DIR, $BASE_URL, $adminFlag;

if (stream_resolve_include_path("csb-settings.php") === false) {

   /**
    * This part finds the installer path regardless of what the original request URL was.
    * It does so by going up the request URI until reaching the root and testing whether
    * the URL "csb-installer/index.php" exists.
    */

   $test_path = $_SERVER['REQUEST_URI'];                    // The current relative request url, for example /csb/science/.
   $proto = isset($_SERVER['HTTPS']) ? "https" : "http";
   $BASE_URL = $proto . "://" . $_SERVER['SERVER_NAME'];
   do {
       error_log("Test path is $test_path");
       $test_url = $BASE_URL . $test_path . "/csb-installer/index.php";
       $headers = @get_headers($test_url);
       if ($headers && strpos( $headers[0], '200')) {       // If that exists
         header ("Location: " . $test_url);   // Go to there
         exit();
      }
      error_log("Tested URL was $test_url");                                // If the relative path doesn't exist, print it, then...
      $test_path = dirname($test_path);                                     // Go up one level
   } while ($test_path != DIRECTORY_SEPARATOR);                             // And try again until you reach the top level
   die("Failed to find installer in any of the tested paths above!");       // If you reached here, the array emptied out - meaning the path couldn't be found on the tree from the source address to the root.
}

// Define directory paths
define('CSB_PATH', __DIR__);
define('SRC_PATH', dirname(__DIR__) . '/src');

// Autoload function to load classes from the src directory under the Cosmo\QuestX namespace
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'CosmoQuestX') === 0) {
        $file = SRC_PATH . '/' . str_replace('\\', '/', substr($class_name, 11)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require "csb-settings.php";
$loader = TRUE;

/* ----------------------------------------------------------------------
   Define the theme

       1. TODO Check if one is defined in the database
       2. TODO Check if it is configured correctly
       3. TODO If setup, use that theme, else use default
   ---------------------------------------------------------------------- */

// Default theme (if nothing set in database)
global $THEME_URL, $THEME_DIR, $SITE_TITLE, $SITE_NAME;

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

/*
 * global the social
 */

global $social_discord, $social_youtube, $social_twitch, $social_twitter;

/* ----------------------------------------------------------------------
   Setup User Roles - needed because of potential customizations
   ---------------------------------------------------------------------- */

include($DB_class);
$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);
$query = "SELECT * FROM roles";
$result = $db->runQuery($query);

foreach ($result as $row) {
    $CQ_ROLES[$row['name']] = $row['id'];
}
$db->closeDB();

$adminFlag = FALSE;
