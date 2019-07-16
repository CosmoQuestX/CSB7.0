<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */

function listDashboards() {
    global $BASE_DIR;

    $dir = $BASE_DIR . "/csb-admin/dashboards";
    $listings = array_diff(scandir($dir), array('..', '.'));
    ?>

    <h3>Options</h3>
    <ul>

    <?php
    foreach ($listings as $item) {
        echo "<li>" . ucfirst($item) . "</li>";
    }

    ?>
    </ul>
    <?php

}