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
                            require_once($BASE_DIR . "csb-themes/footer.php");
                            die ("");
                            exit();
                        } ?>
                </div>
            </div>
        </div>
    </div>
</div>
