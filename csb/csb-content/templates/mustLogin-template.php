<?php

// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader;
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


/* ----------------------------------------------------------------------
 Where should they go to?
 ---------------------------------------------------------------------- */

// Are they on this site?
$referringURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
GLOBAL $ACC_URL;


?>
