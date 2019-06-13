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

    require("csb-settings.php");
    $loader = TRUE;

/* ----------------------------------------------------------------------
   Define the theme

       1. Check if one is defined in the database  TODO
       2. Check if it is configured correctly       TODO
       3. If setup, use that theme, else use default    TODO
   ---------------------------------------------------------------------- */

    // Default theme (if nothing set in database)
    $THEME_DIR = $BASE_DIR . "/csb-themes/default";





