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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
