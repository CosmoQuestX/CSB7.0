<?php
/* Form to set people up with two-factor authentication
 *
 *  It needs to be called after the register.php but before the regUser() is done.
*/

/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

$page_title = $SITE_TITLE . "Two-factor authentication";

loadHeader($page_title);
load3Col($menus, $main, $notes, '2fa-template.php');
loadFooter();
