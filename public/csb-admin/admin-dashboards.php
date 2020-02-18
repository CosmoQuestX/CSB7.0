<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */


/**
 * List the available dashboards from the filesystem and produce links from them
 *
 * @return void
 *
 */
function listDashboards()
{
    global $BASE_DIR;

    $dir = $BASE_DIR . "/csb-accounts/dashboards";
    $listings = array_diff(scandir($dir), array('..', '.'));
    ?>

    <h3>Options</h3>
    <ul>

        <?php
        foreach ($listings as $item) {
            echo "<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?task={$item}\">" . ucFirst($item) . "</a><br />";
        }
        ?>
    </ul>
    <?php

}