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
 * @param $THEME_DIR
 * @param $user
 */
function loadHeader($page_title, $auth = 0) {
    global $BASE_URL, $BASE_DIR, $THEME_DIR, $THEME_URL;

    require ($THEME_DIR . "/header.php");
}

/**
 * @param $THEME_DIR
 */
function loadFooter() {
    global $THEME_DIR;
    
    require ($THEME_DIR . "/footer.php");
}