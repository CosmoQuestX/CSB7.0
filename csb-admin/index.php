<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
   require_once ("../csb-loader.php");
   require_once ($BASE_DIR."csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

    $auth = isLoggedIn();

   if (!$auth) {         // NOT LOGGED IN
       require_once ($BASE_DIR."csb-content/templates/login.php");
   }
   else {                       // Show Login Dashboards
        require_once ("admin-templates.php");
        loadHeader($THEME_DIR, $auth);
        loadFooter($THEME_DIR);
   }