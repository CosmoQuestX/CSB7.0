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
   require_once ($DB_class);
   require_once ($BASE_DIR."csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

    $db = new DB($db_servername, $db_username, $db_password, $db_name);

   if (isLoggedIn($db) === FALSE) {         // NOT LOGGED IN
       require_once ($BASE_DIR."csb-content/templates/login.php");
   }


/* ----------------------------------------------------------------------
   Do they have the correct role?
   ---------------------------------------------------------------------- */

   elseif ($_SESSION['roles'] !== 1) {

        die("ERROR: You don't have permission to be here");
   }


/* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

   else {
       require_once ("admin-templates.php");
       loadHeader($BASE_URL, $THEME_DIR, $auth);
       loadFooter($THEME_DIR);
   }

   $db->closeDB();