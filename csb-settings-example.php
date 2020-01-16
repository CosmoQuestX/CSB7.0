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
 *     1) Create a copy of this file named csb-settings.php
 *        ** Never add your settings file to a public repo! **
 *
 *     2) Fill in on values for the following sections
 *          a) Database Settings
 *
 * ----------------------------------------------------------------------
 * ---------------------------------------------------------------------- **/

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