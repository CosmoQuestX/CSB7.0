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

$NODE_MODULES_DIR = $BASE_DIR . "node_modules/";
$NODE_MODULES_URL = $BASE_URL . "node_modules/";

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
    if (isset($_POST['write_config']) && $_POST['write_config'] == "true") {
        //Let's prepare the config we want to write
        $config_head = "<?php \n";
        $config_body = "";
        $config_foot = "ini_set(\"log_errors\", 1);\nini_set(\"error_log\", \$BASE_DIR.\"logs/error.log\");\n?>";
        $avar = array('SITE_NAME','BASE_DIR', 'BASE_URL', 'db_servername', 'db_username', 'db_password', 'email_host', 'email_username', 'email_password', 'email_port', 'email_from');
        foreach ($avar as $varname) {
            if (!isset($varname)) {
                // TODO We should probably exit more gracefully.
                die("This should not happen, but {$varname} is not set");
            }
        }
        // TODO Suggestion: rework the database layer to support table prefixes. Useful if CSB needs to be run on one shared database.

        foreach ($_POST as $key => $value) {
            if ($key != "write_config" && $key != "submit") {
                if (strpos($key, "email_") !== false) {
                    $index = str_replace("email_", "", $key);
                    $config_body .= "\$emailSettings['{$index}']='$value';\n";
                } else if (strpos($key, "social_") !== false) {
                    $index = str_replace("social_", "", $key);
                    switch ($index) {
                        case "discord":
                            $config_body .= "\${$key}='https://discord.gg/{$value}';\n";
                            break;
                        case "youtube":
                            $config_body .= "\${$key}='https://youtube.com/{$value}';\n";
                            break;
                        case "twitch":
                            $config_body .= "\${$key}='https://twitch.tv/{$value}';\n";
                            break;
                        case "twitter":
                            $config_body .= "\${$key}='https://twitter.com/{$value}';\n";
                            break;
                    }
                } else {
                    $config_body .= "\${$key}='{$value}';\n";
                }
            }
        }
        // Time to actually write the config!
        $csbconfig = fopen($BASE_DIR . "csb-settings.php", 'c');
        fwrite($csbconfig, $config_head);
        fwrite($csbconfig, $config_body);
        fwrite($csbconfig, $config_foot);
        fclose($csbconfig);


        // If we produced a readable configuration, we can carry on to step 2.
        if (is_readable($BASE_DIR . "/csb-settings.php")) { ?>
            <div id="main">
            <div id="" class="container">
                <div id="app" style="padding: 10px; background-color: #ffffff; color: #1d1d1d; border-radius: 10px;">
                    <?php include("installer.php"); ?>
                </div>
            </div>
            <?php
            die();
        } else {
            // Else, better not continue.
            die ("Failed reading the configuration, please check your configuration manually.");
        }
    } else {
        echo "How did you get here?";
    }
}

$scripts = '<script type="text/javascript" src="'. $BASE_URL .'csb-content/js/network.js"></script>';
$scripts .= '<script type="text/javascript" src="js/installer.js"></script>';
$scripts .= '<script type="text/javascript" src="'. $THEME_URL .'js/bs4-form-validation.min.js"></script>';
$scripts .= '<script type="text/javascript" src="js/installer-validator.js"></script>';
loadFooter($scripts);
