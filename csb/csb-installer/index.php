<?php

/* ----------------------------------------------------------------------
 We should make sure the installer is not called when the settings file
 is already present. Just incase somebody tries.
 ---------------------------------------------------------------------- */
if ((@include "../csb-settings.php") == TRUE) {

    global $BASE_URL, $THEME_URL;
    header("Location: $BASE_URL");
    exit();
}


/* ----------------------------------------------------------------------
 First, we should guesstimate our BASE_DIR and BASE_URL
 ---------------------------------------------------------------------- */


if (isset($_SERVER) && isset($_SERVER['SCRIPT_FILENAME'])) {
    $BASE_DIR = stristr($_SERVER['SCRIPT_FILENAME'], "csb-installer", TRUE);
    $BASE_URL = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . str_replace("//", "/", stristr($_SERVER['REQUEST_URI'], "csb-installer", TRUE));
}

require_once("installer-functions.php");

/* ----------------------------------------------------------------------
 Let's set a default theme so the installer doesn't look too boring.
 ---------------------------------------------------------------------- */

$page_title = "CSB Installer";

$THEME_DIR = $BASE_DIR . "csb-themes/default/";
$THEME_URL = $BASE_URL . "csb-themes/default/";

require_once($BASE_DIR . "csb-content/template_functions.php");


loadHeader();

/* ----------------------------------------------------------------------
 Check if this is a POST request or just starting the install
 ---------------------------------------------------------------------- */

if (empty($_POST)) {
    require_once("installer-form.php");

/* ----------------------------------------------------------------------
 If this is a form response, setup the settings file
 ---------------------------------------------------------------------- */

} else {
    echo "post";
    if (isset($_POST['write_config']) && $_POST['write_config'] == "true") {
        echo "write";
    } else {
        echo "How did you get here?";
    }
}

$scripts = '<script type="text/javascript" src="'. $BASE_URL .'csb-content/js/network.js"></script>';
$scripts .= '<script type="text/javascript" src="js/installer.js"></script>';
loadFooter($scripts);
