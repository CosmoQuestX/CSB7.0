<?php
/**
 * Simple installer to set up the settings file.
 * User: dpi209
 * Date: 8/31/19
 * Time: 2:34 PM
 */

/* ----------------------------------------------------------------------
   We should make sure the installer is not called when the settings file
   is already present. Just incase somebody tries.  
   ---------------------------------------------------------------------- */
if ((@include "../csb-settings.php") == TRUE) {
    header("Location: $BASE_URL");
}

/* ----------------------------------------------------------------------
   First, we should guesstimate our BASE_DIR and BASE_URL
   ---------------------------------------------------------------------- */
if (isset($_SERVER) && isset($_SERVER['SCRIPT_FILENAME']))
    $BASE_DIR = stristr($_SERVER['SCRIPT_FILENAME'], "csb-installer", TRUE);

$BASE_URL = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . stristr($_SERVER['REQUEST_URI'], "csb-installer", TRUE);
require_once("installer-functions.php");

/* ----------------------------------------------------------------------
   Let's set a default theme so the installer doesn't look too boring.
  ---------------------------------------------------------------------- */

$page_title = "CSB Installer";

$THEME_DIR = $BASE_DIR . "csb-themes/default/";
$THEME_URL = $BASE_URL . "csb-themes/default/";

global $THEME_URL, $THEME_DIR;

require_once($BASE_DIR . "csb-content/template_functions.php");

loadHeader();

/* ----------------------------------------------------------------------
   If we called ourselves, we should try to write the config
   ---------------------------------------------------------------------- */
if (isset($_POST) && isset ($_POST['write_config'])) {
    if ($_POST['write_config'] == "true") {
        //Let's prepare the config we want to write

        $config_head = "<?php \n";
        $config_body = "";
        $config_foot = "ini_set(\"log_errors\", 1);\nini_set(\"error_log\", \$BASE_DIR.\"logs/error.log\");\n?>";

        $avar = array('BASE_DIR', 'BASE_URL', 'db_servername', 'db_username', 'db_password', 'email_host', 'email_username', 'email_password', 'email_port', 'email_from');
        foreach ($avar as $varname) {
            if (!isset($varname)) {
                // TODO We should probably exit more gracefully.
                die("This should not happen, but {$varname} is not set");
            }
        }
        // TODO Suggestion: rework the database layer to support table prefixes. Useful if CSB needs to be run on one shared database.

        foreach ($_POST as $key => $value) {
            // print "Key: $key - Value: $value <br />\n";
            if ($key != "write_config" && $key != "submit") {
                if (strpos($key, "email_") !== false) {
                    $index = str_replace("email_", "", $key);
                    $config_body .= "\$emailSettings['{$index}']=\"$value\";\n";
                } else {
                    $config_body .= "\${$key}=\"{$value}\";\n";
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

                    <p><?php
                        require_once("installer.php");
                        ?></p>
                    <h3>Write down your password!</h3>
                </div>
            </div>
            <?php
            die();
        } else {
            // Else, better not continue.
            die ("Failed reading the configuration, please check your configuration manually.");
        }
    }
}


/* ---------------------------------------------------------------------
   The default header shows the login div id user on the top right.
   So since we definitely don't have a user yet, hide the div.
   --------------------------------------------------------------------- */

echo "<script type=\"text/javascript\" language=\"JavaScript\">";
echo "document.getElementById(\"user\").style.display = \"none\";";
echo "</script>\n";

/* ---------------------------------------------------------------------
   Time to get things set up!
   --------------------------------------------------------------------- */

echo '<div id="main">';
echo '<div id="" class="container">';
echo '<div id="app" style="padding: 10px; background-color: #ffffff; color: #1d1d1d; border-radius: 10px;">';
echo "<p><h4>You are running the Citizen Science Builder installer</h4> <br />\n";
echo "Using installation path: " . $BASE_DIR . "<br />\n";
echo "Using URL:" . $BASE_URL . "<br />\n";
echo "The installation path is writeable by the webserver: ";
if (is_writable($BASE_DIR)) {
    echo "<span style=\"font-weight:bold; color:green\">TRUE</span><br /></p>\n";
} else {
    echo "<span style=\"font-weight:bold; color:red;\">FALSE</span><br />\n";
    echo "Make sure the directory ${BASE_DIR} is writeable for the webserver user. (for Ubuntu: sudo chmod -R www-data your_dir)</p>";
    // We cant' write our configuration so abort.
    echo "Aborting the installer. </div></div></div>";
    require_once($THEME_DIR . "/footer.php");
    die ("");
}


/* ----------------------------------------------------------------------
   Before we do the actual installation stuff, let the user give us our
   default stuff.
   ---------------------------------------------------------------------- */

echo "<p>Let's set up the basics: </p>";

echo <<<EOT
<form name="installation" action="${_SERVER['SCRIPT_NAME']}" method="post">
<div id="form-input-box">
        <div id="form-input-row"><div id="form-input-left">Site Name</div><div id="form-input-right"><input type="text" name="SITE_NAME" value="Do Science"></div></div>
        <div id="form-input-row"><div id="form-input-left">Base Dir</div><div id="form-input-right"><input type="text" name="BASE_DIR" value="${BASE_DIR}"></div></div>
        <div id="form-input-row"><div id="form-input-left">Base URL</div><div id="form-input-right"><input type="text" name="BASE_URL" value="${BASE_URL}"></div></div>
        <div id="form-input-row"><div id="form-input-left">Site Admin Email</div><div id="form-input-right"><input type="text" name="rescue_email" value="admin@${_SERVER['SERVER_NAME']}"></div></div>
        <div id="form-input-row"><div id="form-input-left">Database Server</div><div id="form-input-right"><input type="text" name="db_servername" value="localhost"></div></div>
        <div id="form-input-row"><div id="form-input-left">Database User</div><div id="form-input-right"><input type="text" name="db_username" value="csb"></div></div>
        <div id="form-input-row"><div id="form-input-left">Database Password</div><div id="form-input-right"><input type="password" name="db_password"></div></div>
        <div id="form-input-row"><div id="form-input-left">Database name</div><div id="form-input-right"><input type="text" name="db_name" value="csb"></div></div>
    <!-- Maybe we want to support table prefixes in the future.
	     That needs some work on the database layer though, so
		 we'll skip it for now.
        <div id="form-input-row"><div id="form-input-left">Table prefix</div> <div id="form-input-right"><input type="text" name="db_prefix" value=""></div></div>
	-->
        <div id="form-input-row"><div id="form-input-left"></div><div id="form-input-right">&nbsp;</div></div>
        <div id="form-input-row"><div id="form-input-left">Email Host</div><div id="form-input-right"><input type="text" name="email_host" value="smtp.yourprovider.example"></div></div>
        <div id="form-input-row"><div id="form-input-left">Email User</div><div id="form-input-right"><input type="text" name="email_username" value=""></div></div>
        <div id="form-input-row"><div id="form-input-left">Email Password</div><div id="form-input-right"><input type="password" name="email_password" value=""></div></div>
        <div id="form-input-row"><div id="form-input-left">Email Port</div><div id="form-input-right"><input type="text" name="email_port" value="587"></div></div>
        <div id="form-input-row"><div id="form-input-left">Email sender address</div><div id="form-input-right"><input type="text" name="email_from" value="csb@${_SERVER['SERVER_NAME']}"></div></div>
        <br /><input type="hidden" name="write_config" value="true"><input type="submit" class="btn-default" name="submit" value="Write config">
</div>
</form>


EOT;

echo "</div></div></div>";
loadFooter();