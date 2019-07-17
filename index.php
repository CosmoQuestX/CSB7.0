<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */

/** ---------------------------------------------------------------------
 *  ---------------------------------------------------------------------
 *  This loads the public pages
 *
 *
 *
 *
 *
 *
 * ----------------------------------------------------------------------
 * ---------------------------------------------------------------------- **/

/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

    require_once("./csb-loader.php");
    require_once ($DB_class);
    require_once ($BASE_DIR."csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

    $db = new DB($db_servername, $db_username, $db_password, $db_name);

    $user = isLoggedIn($db);

    if ( $user === FALSE ) {         // NOT LOGGED IN
        $auth = 0;
    }
    else {
        $auth = 1;
    }


/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */

    $page_title = "";

    require_once($THEME_DIR . "/header.php");
    require_once($THEME_DIR . "/app.php");
    require_once($THEME_DIR . "/footer.php");


