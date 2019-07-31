<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */

loadHeader();

/** Get the setup file for the app TODO make this a function */
require_once ($BASE_DIR . "/csb-content/templates/apps/bennu.php");
?>

<div id="main">
    <div class="container">

        <div id="" class="app-left left">
            Left Bar
        </div>

        <div class="app-main left">

            <div id="app-button-container" class="left">
                <?php
                foreach($buttons as $button) {
                    echo "<img class='app-button' src='".$BASE_URL."/csb-content/images/buttons/".$button['img']."' alt='".$button['name']."'><br/>";
                }
                ?>
            </div>
            <div id="app-canvas" class="left">

            </div>

            <div class="clear"></div>
        </div>

        <div class="app-right right">
This is the right

        </div>
        <div class="clear"></div>
    </div>
</div>