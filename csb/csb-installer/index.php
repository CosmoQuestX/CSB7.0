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
                    $config_body .= "\$emailSettings['{$index}']=\"$value\";\n";
                } else if (strpos($key, "social_") !== false) {
                    $index = str_replace("social_", "", $key);
                    switch ($index) {
                        case "discord":
                            $config_body .= "\${$key}=\"https://discord.gg/{$value}\";\n";
                            break;
                        case "youtube":
                            $config_body .= "\${$key}=\"https://youtube.com/{$value}\";\n";
                            break;
                        case "twitch":
                            $config_body .= "\${$key}=\"https://twitch.tv/{$value}\";\n";
                            break;
                        case "twitter":
                            $config_body .= "\${$key}=\"https://twitter.com/{$value}\";\n";
                            break;
                    }
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
   Time to get things set up!
   --------------------------------------------------------------------- */

// Requirement definition
$php_min_version = "70200";
$php_min_version_readable = "7.2";
$php_rec_version = "80100";
$php_rec_version_readable = "8.1";
$extensions = array("mysqli");
$optionals = array("Mail");
$rq1=false;
$rq2=false;
$rqe=array();

?>


<!-- DB Connection Tester -->
<script type="text/javascript" src="../csb-content/js/network.js"></script>
<script type="text/javascript" language="JavaScript">
    $(document).ready(function(){
        $("#db-tester").click(function() {
            data = {
                "db_servername": $("[name='db_servername']").val(),
                "db_username": $("[name='db_username']").val(),
                "db_password": $("[name='db_password']").val(), // Is this secure? Do we care at this point?
                "db_name": $("[name='db_name']").val(),
                "db_port": $("[name='db_port']").val()
            }

            /*
             The response will always be with a 200 status.
             It will look like this for success: { result: true }
             And like this for failures:
             {
                 result: false,
                 code: <code>,
                 message: <error message>
             }
             */

            postData("db-tester.php", data).then( response => {
                if (response.result)
                {
                    $("#test-status").html("Looks good! 👍")
                        .attr("class", "alert alert-success col-12") //Style the message
                        .css({
                            "margin-top": "1rem",
                            "display": "block",
                            "width": "auto",
                            "height": "auto"
                        }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
                }
                else
                {
                    $("#test-status").html("Error: " + response.message)
                        .attr("class", "alert alert-danger col-12")  //Style the message
                        .css({
                                "margin-top": "1rem",
                                "display": "block",
                                "width": "auto",
                                "height": "auto"
                            }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
                }
            }).catch( err => {
                $("#test-status").html("An unexpected error occurred!")
                    .attr("class", "alert alert-danger col-12")  //Style the message
                    .css({
                            "margin-top": "1rem",
                            "display": "block",
                            "width": "auto",
                            "height": "auto"
                        }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
            });
        });
    });
</script>

<!-- end DB Connection Tester -->

<div class="container text-dark">
    <div class="row">
        <div class="col">

            <div class="card">
                <h4 class="card-header">Citizen Science Builder Installer</h4>
                <div class="card-body">
                    <p>Using installation path: <code><?php echo $BASE_DIR; ?></code></p>
                    <p>Using URL: <code><?php echo $BASE_URL; ?></code></p>
                    <p>The installation path is writeable by the webserver:
                    <?php

                        if (is_writable($BASE_DIR)) {

                            // Directory is writeable
                            ?>
                            <span class="font-weight-bold text-success">TRUE</span>
                            <?php

                        } else {

                            // Directory is not writeable, so abort
                            ?>
                            <span class="font-weight-bold text-danger">FALSE</span>
                    </p><p class="text-danger">Make sure the directory <br /><code><?php echo $BASE_DIR; ?></code><br /> is writeable for the webserver user. (for Ubuntu: sudo chown -R www-data <?php echo $BASE_DIR; ?>)</p>
    Aborting the installer.</div></div></div></div></div>
                            <?php
                            require_once($THEME_DIR . "/footer.php");
                            die ("");

                        }

                    ?>
                    </p>
                </div>



                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link active" href="#requirements">Requirements</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link" href="#directories">Directories</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link" href="#database">Database</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link" href="#smtp">SMTP</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" class="nav-link" href="#socials">Socials</a>
                        </li>
                    </ul>
                </div>

                <form name="installation" id="installation" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                    <div class="card-body tab-content">

                        <div id="requirements" class="tab-pane active in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label>Is PHP Version at least <?php echo $php_min_version_readable; ?>?<br>Installed: <?php $ver = checkForPHP($php_min_version, $php_rec_version); $ver == 1 ? $vn = $php_rec_version_readable : $vn = $php_min_version_readable; echo phpversion(); ?></label>
                                    <?php
                                    if ($ver == 1) {
                                        echo '<span class="font-weight-bold text-success">OK</span>';
                                        $rq1 = true;
                                    }
                                    elseif ($ver == 2) {
                                        echo '<span class="font-weight-bold text-warning">OUTDATED</span>';
                                        $rq1 = true;
                                    }
                                        else {
                                            echo '<span class="font-weight-bold text-danger">ERROR</span>';
                                        $rq1 = false;
                                    }
                                    ?>
                                    <br />
                                    <label>Checking for required PHP Extensions: <br></label>
                                    <ul>
                                    <?php
                                    foreach ($extensions as $extension) {

                                        if (checkForExtension($extension)) {
                                            echo '<li>Extension ' . $extension . ': <span class="font-weight-bold text-success">OK</span></li>';
                                            $rqe[]=true;
                                        }
                                            else {
                                                echo '<li><span class="font-weight-bold text-danger">ERROR</span></li>';
                                            $rqe[] = false;
                                        }
                                        if (in_array(false,$rqe)) {
                                            $rq2 = false;
                                        }
                                        else
                                        {
                                            $rq2=true;
                                        }
                                    }
                                    ?>
                                    </ul>

                                    <label>Optional Components: </label>
                                   <ul>
                                    <?php
                                    foreach ($optionals as $optional) {

                                        if (checkForClass($optional)) {
                                            echo '<li>Class ' . $optional .': <span class="font-weight-bold text-success">OK</span></li>';
                                        }
                                            else {
                                                echo '<li>Class ' . $optional . ': <span class="font-weight-bold text-danger">ERROR</span></li>';
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <div class="col-md-6" id="requirements-help">
                                    <h5>Requirements</h5>
                                    Currently, the requirements are as follows:
                                    <ul>
                                        <li>PHP: Version <?php echo $php_rec_version_readable; ?> is the version CSB was developed for. </li>
                                        <li>PHP: Version <?php echo $php_min_version_readable; ?> is tested, but may not be supported in future versions.</li>
                                        <li>Extensions: mysqli</li>
                                        <li>Optional components: Mail (from PEAR)</li>
                                        <li>Optional components are, as the name suggests, optional, but they might provide useful functions that you are missing out on if you don't have them installed.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="directories" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="site_name">Site Name</label>
                                    <input type="text" class="form-control" required id="site_name" name="SITE_NAME" placeholder="Do Science">
                                    <label for="base_dir">Base Directory</label>
                                    <input type="text" class="form-control" required id="base_dir" name="BASE_DIR" id="BASE_DIR" value="<?php echo $BASE_DIR; ?>">
                                    <label for="base_url">Base URL</label>
                                    <input type="text" class="form-control" required id="base_url" name="BASE_URL" id="BASE_URL" value="<?php echo $BASE_URL; ?>">
                                    <label for="rescue_email">Site Admin Email</label>
                                    <input type="text" class="form-control" required id="rescue_email" name="rescue_email" pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])" placeholder="your@email.example">
                                </div>
                                <div class="col-md-6" id="directory-help">
                                    <h5>Directory Setup</h5>
                                    <ul>
                                        <li>Site name: This is displayed in page titles and in headers</li>
                                        <li>Base Directory: Complete installation directory (e.g. from pwd)</li>
                                        <li>Base URL: What is the URL to get to CSB's root directory</li>
                                        <li>Site Admin Email: This is used to rescue your admin user</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="database" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="db_servername">Database Server</label>
                                    <input type="text" class="form-control" required id="db_servername" name="db_servername" value="localhost">
                                    <label for="db_port">Database Port</label>
                                    <input type="text" class="form-control" required id="db_port" name="db_port" max="65535" maxlength="5" pattern="[0-9]{1,5}" value="3306">
                                    <label for="db_username">Username</label>
                                    <input type="text" class="form-control" required id="db_username" name="db_username" value="csb">
                                    <label for="db_password">Password</label>
                                    <input type="password" class="form-control" required id="db_password" name="db_password">
                                    <label for="db_name">Database Name</label>
                                    <input type="text" class="form-control" required id="db_name" name="db_name" value="csb">
                                </div>
                                <div class="col-md-6" id="database-help">
                                    <h5>Database Setup</h5>
                                    <ul>
                                        <li>Database Server: Often localhost, 127.0.0.1, or a remote server IP. If in our Docker container, use <b>db</b></li>
                                        <li>Database Port: default is 3306, but if your database runs on a different port you can change it here</li>
                                        <li>Username: this is your database user (security tip: create a program-specific db user)</li>
                                        <li>Database Name: This is where all CSB tables will go. Should be empty/new utf8 / utf8_bin DB schema.</li>
                                    </ul>
                                    <br />
                                    <input type="button" class="btn btn-cq" name="db_tester" id="db-tester" value="Test Connection">
                                </div>
                                <div id="test-status" class="alert alert-light col-12" style="margin-top: 1rem; display:block; width:auto; height:auto;" >&nbsp;</div>
                            </div>
                        </div>
                        <div id="smtp" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="email_host">Email Host</label>
                                    <input type="text" class="form-control" id="email_host" name="email_host" placeholder="smtp.yourprovider.example">
                                    <label for="email_username">Username</label>
                                    <input type="text" class="form-control" id="email_username" name="email_username" value="">
                                    <label for="email_password">Password</label>
                                    <input type="password" class="form-control" id="email_password" name="email_password" value="">
                                    <label for="email_port">Port</label>
                                    <input type="text" class="form-control" id="email_port" name="email_port" max="65535" pattern="[0-9]{1,5}" value="587">
                                    <label for="email_from">Sending Address</label>
                                    <input type="text" class="form-control" id="email_from" name="email_from" pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])" placeholder="your@email.example">
                                </div>
                                <div class="col-md-6" id="smtp-help">
                                    <h5>SMTP Setup</h5>
                                    <p>These settings are specific to your email provider. For production installations
                                    please use an email provider without rate caps. Some Google Business accounts have
                                    no rate cap, and services like Sendgrid work well.</p>
                                </div>
                            </div>
                        </div>
                        <div id="socials" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="social_discord">Discord Invite ID:</label>
                                    <input type="text" class="form-control" id="social_discord" name="social_discord" pattern="[0-9a-zA-Z]{10}" placeholder="aBcD5eF8gH">
                                    <label for="social_youtube">YouTube channel</label>
                                    <input type="text" class="form-control" id="social_youtube" name="social_youtube" pattern="[0-9a-zA-Z/]*" placeholder="/c/CosmoQuest">
                                    <label for="social_twitch">Twitch channel name</label>
                                    <input type="text" class="form-control" id="social_twitch" name="social_twitch" pattern="[0-9a-zA-Z]*" placeholder="cosmoquestx">
                                    <label for=social_twitter>Twitter hande</label>
                                    <input type="text" class="form-control" id="social_twitter" name="social_twitter" pattern="[0-9a-zA-Z]*" placeholder="cosmoquestx">
                                </div>
                                <div class="col-md-6" id="smtp-help">
                                	<h5>Optional: Social Media and Community</h5>
                                	<p>Here you can predefine some of your social media and community contacts
                                	that you can later refer to in texts and descriptions on the site.</p>
                                    <ul>
                                        <li>Discord: Enter your invite ID here (https://discord.gg/&lt;invite_id&gt;</li>
                                        <li>Youtube: Your channel (https://youtube.com/&lt;c/channelname&gt; or https://youtube.com/&lt;channel/channelid&gt;</li>
                                        <li>Twitch: Your Twitch channel (https://twitch.tv/&lt;channel&gt;)</li>
                                        <li>Twitter: Your Twitter handle (https://twitter.com/&lt;handle&gt;)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-4 d-flex justify-content-center">
                        <input type="hidden" name="write_config" value="true">
                        <input type="submit" class="btn btn-cq" name="submit" value="Write Configuration" <?php if ($rq1 === false || $rq2 === false ) { echo "disabled"; } ?> >
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>


<!-- Validation -->
<script src="<?php echo $BASE_URL; ?>csb-themes/default/js/bs4-form-validation.min.js"></script>
<script>
    let install = new Validation("installation");
    install.requireText("BASE_DIR", 0, 999, [], []);
    install.requireText("BASE_URL", 0, 999, [], []);
    install.requireEmail("rescue_email", 4, 999, [], []);
    install.requireText("db_servername", 0, 999, [], []);
    install.requireText("db_username", 0, 999, [], []);
    install.requireText("db_name", 0, 999, [], []);
</script>

<?php
loadFooter();
