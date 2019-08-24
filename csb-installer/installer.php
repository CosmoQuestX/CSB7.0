<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:40 PM
 */

/* ----------------------------------------------------------------------
 First, we should guesstimate our BASE_DIR and BASE_URL
 ---------------------------------------------------------------------- */
if (isset($_SERVER) && isset($_SERVER['SCRIPT_FILENAME']) )
    $BASE_DIR=stristr($_SERVER['SCRIPT_FILENAME'],"csb-installer",TRUE);
    $BASE_URL="http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . stristr(dirname($_SERVER['REQUEST_URI']),"csb-installer",TRUE);
    require_once("installer-functions.php");
    
    /* ----------------------------------------------------------------------
     If we called ourselves, we should try to write the config
     ---------------------------------------------------------------------- */
    if (isset($_POST) && isset ($_POST['write_config'])) {
        if ($_POST['write_config']=="true") {
            //Let's prepare the config we want to write
            
            $config_head = "<?php \n";
            $config_body = "";
            $config_foot = "ini_set(\"log_errors\", 1);\nini_set(\"error_log\", \$BASE_DIR.\"logs/error.log\");\n?>";
            
            $avar = array('BASE_DIR','BASE_URL','db_servername','db_username','db_password','email_host','email_username','email_password','email_port','email_from');
            foreach ($avar as $varname) {
                checksetvar($varname);
            }
            // TODO Suggestion: rework the database layer to support table prefixes. Useful if CSB is to be run on one shared database.
            
            echo "<br /><br />Displaying Configuration: <br />";
            echo "<br /><br />";
            foreach ($_POST as $key => $value) {
                print "Key: $key - Value: $value <br />\n";
                if ($key != "write_config" && $key != "submit") {
                    if(strpos($key, "email_") !== false ) {
                        $index=str_replace("email_","",$key);
                        $config_body .= "\$emailSettings['{$index}']=\"$value\";\n";
                    } else {
                        $config_body .= "\${$key}=\"{$value}\";\n";
                    }
                }
            }
            
            // Time to actually write the config!
            $csbconfig=fopen("../csb-settings.php",'c');
            fwrite($csbconfig,$config_head);
            fwrite($csbconfig,$config_body);
            fwrite($csbconfig,$config_foot);
            fclose($csbconfig);
            
            
            // If we produced a readable configuration, we can carry on to step 2.
            if (is_readable("../csb-settings.php")) {
                header("Location: installer2.php");
            } else {
                // Else, better not continue.
                die ("Failed reading the configuration, please check your configuraton manually.");
            }
        }
    }
    
    
    /* ----------------------------------------------------------------------
     Let's set a default theme so the installer doesn't look too boring.
     ---------------------------------------------------------------------- */
    
    $page_title = "CSB Installer";
    
    $THEME_DIR = $BASE_DIR . "csb-themes/default/";
    $THEME_URL = $BASE_URL . "csb-themes/default/";
    
    global $THEME_URL, $THEME_DIR; $page_title;
    
    
    require_once($BASE_DIR . "/csb-content/template_functions.php");
    loadHeader();
    
    /* ---------------------------------------------------------------------
     The default header shows the login div id user on the top right.
     So since we definitely don't have a user yet, hide the div.
     --------------------------------------------------------------------- */
    
    echo "<script type=\"text/javascript\" language=\"JavaScript\">";
    echo "document.getElementById(\"user\").style.display = \"none\";";
    echo "</script>";
    
    /* ---------------------------------------------------------------------
     Time to get things set up!
     --------------------------------------------------------------------- */
    
    
    echo "You are running the Citizen science Builder installer <br />";
    echo "I'm guessing the installation path on the Server is " . $BASE_DIR ."<br />";
    echo "I'm guessing the base URL is " . $BASE_URL ."<br />";
    echo "Checking whether the installation path is writeable: ";
    if (is_writable ($BASE_DIR)) { echo "<span style=\"text-color:green\">TRUE</span><br />";
    }
    else {
        echo "<span style=\"text-color:red\">FALSE</span><br />";
        echo "<h2>Make sure the directory ${BASE_DIR} is writeable for the webserver user</h2>";
        // We cant' write our configuration so abort.
        require_once($THEME_DIR . "/footer.php");
        die ("Aborting the installer");
    }
    
    /* ----------------------------------------------------------------------
     Before we do the actual installation stuff, let the user give us our
     default stuff.
     ---------------------------------------------------------------------- */
    
    echo "Let's set up the basics: <br /><br />";
    
    echo <<<EOT
<form name="installation" action="${_SERVER['SCRIPT_NAME']}" method="post">
<table>
    <tr>
        <td>Base Dir</td>
        <td><input type="text" style="width:500px;" name="BASE_DIR" value="${BASE_DIR}"></td>
    </tr>
    <tr>
        <td>Base URL</td>
        <td><input type="text" style="width:500px;" name="BASE_URL" value="${BASE_URL}"></td>
    </tr>
    <tr>
        <td>Site Admin Email</td>
        <td><input type="text" style="width:500px;" name="rescue_email" value="admin@${_SERVER['SERVER_NAME']}"></td>
    </tr>
    <tr>
        <td>Database Server</td>
        <td><input type="text" style="width:500px;" name="db_servername" value="localhost"></td>
    </tr>
    <tr>
        <td>Database User</td>
        <td><input type="text" style="width:500px;" name="db_username" value="csb"></td>
    </tr>
    <tr>
        <td>Database Password</td>
        <td><input type="password" style="width:500px;" name="db_password"></td>
    </tr>
    <tr>
        <td>Database name</td>
        <td><input type="text" style="width:500px;" name="db_name" value="csb"></td>
    </tr>
    <tr>
        <td>Table prefix</td>
        <td><input type="text" style="width:500px;" name="db_prefix" value="" readonly></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">You can do some Email settings here:
    </tr>
    <tr>
        <td>Email Host</td>
        <td><input type="text" style="width:500px;" name="email_host" value="smtp.yourprovider.example"></td>
    </tr>
    <tr>
        <td>Email User</td>
        <td><input type="text" style="width:500px;" name="email_username" value=""></td>
    </tr>
    <tr>
        <td>Email Password</td>
        <td><input type="password" style="width:500px;" name="email_password" value=""></td>
    </tr>
    <tr>
        <td>Email Port</td>
        <td><input type="text" style="width:500px;" name="email_port" value="587"></td>
    </tr>
    <tr>
        <td>Email sender address&nbsp;&nbsp;</td>
        <td><input type="text" style="width:500px;" name="email_from" value="csb@${_SERVER['SERVER_NAME']}"></td>
    </tr>
    
</table>
    <input type="hidden" name="write_config" value="true">
<input type="submit" name="submit" value="Write config">
</form>

EOT;
    
    
    
    
    
    require_once($THEME_DIR . "/footer.php");
    
    