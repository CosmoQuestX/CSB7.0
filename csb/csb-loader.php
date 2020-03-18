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

if (stream_resolve_include_path("csb-settings.php") === false) {
   
   /**
    * This part finds the installer path regardless of what the original request URL was.
    * It does so by comparing the relative URI to the root on the file system,
    * checking if the directory exists there, and going up one level if not until reaching the root.
    */
   
   $CUR_REL_URI = $_SERVER['REQUEST_URI'];   // The current relative request url, for example /CSB7.0/csb/science/ if that's the relative url to the server root.
   $ROOT = $_SERVER['DOCUMENT_ROOT'];        // The root of the server on the file system.
   $arrReq = explode("/", $CUR_REL_URI);     // The relative url is now an array ["", "CSB7.0", "CSB", "science", ""]
   while (count($arrReq) >= 2) {             // While the array is not empty
      $TEST_PATH = join("/", $arrReq) . "csb-installer/";  // Test path is the relative path with "csb-installer/" tacked on in the end
      if (is_dir($ROOT . $TEST_PATH)) {                    // If that exists
         header ("Location: " . $TEST_PATH);               // Go to there
         exit();
      }  
      echo $TEST_PATH . "<br>";                       // If the relative path doesn't exist, print it, then...
      array_splice($arrReq, count($arrReq) - 2, 1);   // Remove the last part of the path by splicing the array
   }                                                  // And try again
   die("Failed to find installer in any of the tested paths above!");   // If you reached here, the array emptied out - meaning the path couldn't be found on the tree from the source address to the root.
}

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








