<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 5:14 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


/**
 * Print HTML header for selected theme
 * 
 * Loads the header file from the theme directory and prints a basic HTML 
 * header for administrative pages
 *
 * @param string page_title - the Title of the page 
 * @param boolean auth 
 * 
 * @return void
 * 
 */
function loadHeader($page_title, $auth = 0) {
    global $BASE_URL, $BASE_DIR, $THEME_DIR, $THEME_URL;

    require ($THEME_DIR . "/header.php");
}

/**
 * Print HTML footer for selected theme
 * 
 * Loads the footer file from the theme directory and prints a basic HTML
 * footer for administrative pages
 * 
 * @return void
 * 
 */
function loadFooter() {
    global $THEME_DIR;
    
    require ($THEME_DIR . "/footer.php");
}
