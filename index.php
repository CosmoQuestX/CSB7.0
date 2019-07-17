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

    global $user;
    $user = isLoggedIn($db);


/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
    global $page_title;

    $page_title = "";

    require_once($BASE_DIR . "/csb-content/template_functions.php");
    require_once($THEME_DIR . "/app-template.php");
    require_once($THEME_DIR . "/footer.php");


