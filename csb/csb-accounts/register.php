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

require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user, $ACC_URL;
$user = isLoggedIn($db);

/* ----------------------------------------------------------------------
   Load content
   ---------------------------------------------------------------------- */

$menus = "Put Menus Here";
$main  = "";
$notes = "Put Instructions Here";

$notes = "
    <h5>How we use your information</h5>
    <p>
    This is a citizen science site. Your contributes through our app will be
    used to produce new science results or to accomplish tasks with spacecraft.
    </p>
    <ul>
    <li><span class='emp'>Your username</span> will be used to give you credit for your work. 
    It will be visible in galleries and on lists of achievements.</li>
    <li><span class='emp'>Your email</span> will never be shared! We will contact
    you about how your work is used, and we will send you periodic newsletters
    (you can unsubscribe from your settings page).</li>
    <li><span class='emp'>Your password</span> is always encrypted & never transmitted as plain
    text.</li>
    </ul>
    ";


/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

$page_title = $SITE_TITLE . "Registration";

//TODO Set error for if loading while logged in


loadHeader($page_title);
load3Col($menus, $main, $notes, 'reg-template.php');
loadFooter();


