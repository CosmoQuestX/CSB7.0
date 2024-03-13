<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:46 PM
 */

// Start by defining what the minimum versions are

    $php_min_version = "70200";
    $php_min_version_readable = "7.2";
    $php_rec_version = "80100";
    $php_rec_version_readable = "8.2";
    $extensions = array("mysqli");
    $rq1=false;
    $rq2=false;
    $rqe=array();
?>


<div class="container text-dark">
    <div class="row">
        <div class="col">


            // Requirements Tab for installer
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
                            require_once($BASE_DIR . "csb-themes/default/footer.php");
                            die ("");
                            exit();
                        } ?>
                </div>

                <!-- Setup the tabs to go between menus -->
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

                <!-- open the form -->
                <form name="installation" id="installation" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                    <div class="card-body tab-content">

                        <!-- Requirements Tab -->
                        <div id="requirements" class="tab-pane active in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label>Is PHP Version at least <?php echo $php_min_version_readable; ?>?<ul><li>Installed: <?php $ver = checkForPHP($php_min_version, $php_rec_version); $ver == 1 ? $vn = $php_rec_version_readable : $vn = $php_min_version_readable; echo phpversion(); ?></li></ul></label>
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
                                </div>
                                <div class="col-md-6" id="requirements-help">
                                    <h5>Requirements</h5>
                                    Currently, the requirements are as follows:
                                    <ul>
                                        <li>PHP: Version <?php echo $php_rec_version_readable; ?> is the version CSB was developed for. </li>
                                        <li>PHP: Version <?php echo $php_min_version_readable; ?> is tested, but may not be supported in future versions.</li>
                                        <li>Extensions: mysqli</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Directories Tab -->
                        <div id="directories" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="site_name">Site Name</label>
                                    <input type="text" class="form-control" required id="site_name" name="SITE_NAME" placeholder="Do Science">
                                    <label for="base_dir">Base Directory</label>
                                    <input type="text" class="form-control" required id="base_dir" name="BASE_DIR" value="<?php echo $BASE_DIR; ?>">
                                    <label for="base_url">Base URL</label>
                                    <input type="text" class="form-control" required id="base_url" name="BASE_URL" value="<?php echo $BASE_URL; ?>">
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

                        <!-- Database Tab -->
                        <div id="database" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <label for="db_servername">Database Server</label>
                                    <input type="text" class="form-control" required id="db_servername" name="db_servername" value="localhost">
                                    <label for="db_port">Database Port</label>
                                    <input type="text" class="form-control" required id="db_port" name="db_port" max="65535" maxlength="5" pattern="[0-9]{1,5}" value="3306">
                                    <label for="db_username">Username</label>
                                    <input type="text" class="form-control" required id="db_username" name="db_username" value="csb">
                                    <label for="db_password">Password (cannot be empty)</label>
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
                                <div id="db-test-status" class="alert alert-light col-12" style="margin-top: 1rem; display:block; width:auto; height:auto;" >&nbsp;</div>
                            </div>
                        </div>

                        <!-- SMTP Tab -->
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
                                    <br />
                                    <p>Email Encryption</p>
                                    <input id="email_tls" type="radio" name="email_encryption" value="TLS" checked>
                                    <label for="email_tls">TLS <span style="color: #6c757d;">(recommended)</span></label>
                                    <br />
                                    <input id="email_ssl" type="radio" name="email_encryption" value="SSL">
                                    <label for="email_ssl">SSL</label>
                                    <br />
                                    <label for="email_from">Sending Address</label>
                                    <input type="text" class="form-control" id="email_from" name="email_from" pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])" placeholder="your@email.example">
                                </div>
                                <div class="col-md-6" id="smtp-help">
                                    <h5>SMTP Setup</h5>
                                    <p>These settings are specific to your email provider. For production installations
                                        please use an email provider without rate caps. Some Google Business accounts have
                                        no rate cap, and services like Sendgrid work well.</p>
                                    <p><b>Note</b>: If <code>Test Connection</code> fails but <code>Send Test Email</code> successfully sends (make sure to check your inbox), it is ok to proceed. Some ISPs block the port used for testing.</p>
                                    <br />
                                    <input type="button" class="btn btn-cq" name="email_tester" id="email-tester" value="Test Connection">
                                    <input type="button" class="btn btn-cq" name="example_email" id="example-email" value="Send Test Email">
                                </div>
                                <div id="email-test-status" class="alert alert-light col-12" style="margin-top: 1rem; display:block; width:auto; height:auto;" >&nbsp;</div>
                            </div>
                        </div>

                        <!-- Socials Tab -->
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




                    </div> <!-- end of card-body -->

                    <!-- submission buttons -->
                    <div class="my-4 d-flex justify-content-center">
                        <input type="hidden" name="write_config" value="true">
                        <input type="submit" class="btn btn-cq" name="submit" value="Write Configuration" <?php if ($rq1 === false || $rq2 === false ) { echo "disabled"; } ?> >
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
