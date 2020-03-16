<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:07 PM
 */

/** ---------------------------------------------------------------------
 *  ---------------------------------------------------------------------
 *  Instructions:
 *    This is an example of how the csb-settings.php file should look like.
 *    You do not need to create it yourself, as it will be auto-generated
 *    by the installer.
 * 
 *    Use this as reference for when moving across servers of just as 
 *    a reference for how your eventual file should look like.
 * 
 *        ** Never add your settings file to a public repo! **
 *
 *
 * ----------------------------------------------------------------------
 * ---------------------------------------------------------------------- **/

/* ----------------------------------------------------------------------
   Site Settings
   ---------------------------------------------------------------------- */

$SITE_NAME = "CSB Site";

/* ----------------------------------------------------------------------
   Admin User, "CodeHerder" details
   ---------------------------------------------------------------------- */

$rescue_email = "Email@rescue.me";   // The email address for the site admin

/* ----------------------------------------------------------------------
   Database Settings
   ---------------------------------------------------------------------- */

$db_servername = "localhost";
$db_username = "root";
$db_password = "password";
$db_name = "myDB";

/* ----------------------------------------------------------------------
   Email Settings
   ---------------------------------------------------------------------- */

$emailSettings['host'] = "smtp.yourprovider.net";
$emailSettings['username'] = "username";
$emailSettings['password'] = "password";
$emailSettings['port'] = "587";  // ssl uses 465
$emailSettings['from'] = "no-reply@yoursite.org";

/* ----------------------------------------------------------------------
   Directories
   ---------------------------------------------------------------------- */

$BASE_DIR = "/path/to/CSB/";   // Full path to CSBs home directory, example "/var/www/CSB/"
$BASE_URL = "http://localhost/CSB7.0/";  // Full path to CSB's Webhome

/* ----------------------------------------------------------------------
   Server Settings
   ---------------------------------------------------------------------- */

ini_set("log_errors", 1);
ini_set("error_log", $BASE_DIR . "logs/error.log");