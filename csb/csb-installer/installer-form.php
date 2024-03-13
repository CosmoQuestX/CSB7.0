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


                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
