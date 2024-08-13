<?php global $user;
/**
 * Created by PhpStorm.
 * User: starstryder/EMadaus/TheRealFakeAdmin
 * Date: 8/16/22
 * Time: 17:45
 */

/** Get the setup files for the app dynamically TODO make this a function */

/**  TODO add language support */
//$lang = file_get_contents($lang);
//$lang = json_decode($lang, true);

?>


<div class="container mt-3">
    <div class="row">

        <!-- Left block --------------------------------------------------------------- -->
        <div id="app-left" class="col-md-3 p-4">
            <h3 class="font-weight-bold" style="display: inline;">It's Beta time</h3>
            <p>Welcome to our new version of CosmoQuest's Citizen Science Builder. We will be releasing new science projects regularly.</p>
            <p><strong>FAQs:</strong></p>
            <ul>
                <li><strong>Do I need a new login?</strong><br>Yes (although we hope to merge accounts in the future)</li>
                <li><strong>What happened to my old projects?</strong><br>They are still there, but they are being updated. Stay tuned.</li>
                <li><strong>What's new?</strong><br>Everything! We have a new interface, new tools, and new projects ... and new bugs.</li>
            </ul>

            </div>

        <!-- main block --------------------------------------------------------------- -->
        <div id="app-main" class="col-md-6 p-4">
            <?php
                if ( $user === FALSE) {         // NOT LOGGED IN
            ?>
                <div class="right" style="margin-top: 10px;">
                    <a id="alert-button-home" data-toggle="modal" data-target="#loginModal" class="btn-default" href="#">
                        <span class="btn-default">Login</span>
                    </a>
                </div>
                    <h3 class="font-weight-bold">Welcome!</h3>

            <?php
                }
                else {                           // LOGGED IN
                    ?>
                    <h3 class="font-weight-bold">Hello, <?php echo $user['name'];?>!</h3>
                    <?php
                }
            ?>

            <div class="text-blk">
                <p>Want to do science? We got science! Everything submitted through this interface should (bugs withstanding) be used for science.</p>
                <p>Logged in? You should see an option called "Mosaic" at the top of the screen. Click that and you'll be taken to a super simple
                    project to see if science images have been correctly mosaiced together.</p>


            </div>

        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <?php //$txt = $lang['app_page']['text-boxes']['app-right']; ?>

        <div id="app-right" class="col-md-3 p-4">
            <h3 class="font-weight-bold">Bugs in Progress</h3>
            <div class="text-blk">
                <p>Report bugs on <a href="https://discord.gg/yrfTgajCzT">Discord</a> or <a href="mailto:cosmoquestx@gmail.com">email us</a>.</p>
                <ul>
                    <li>Fix dynamic resizing</li>
                    <li>Make UI pretty</li>
                </ul>

            </div>
        </div>


    </div>
</div>

<!-- p> Discord is the main forum where members of the CosmoQuest community come to do science and also hang out.
If you dont know what Discord is, think of it as a modern version of Internet Relay Chat. You create one account on discord.gg, and can use that one account to join multiple servers.
You have one username, but can make a nickname for each server. Each server is composed of many channels, some text channels like IRC but also voice channels where you can communicate in near real time with a microphone.
/p -->

