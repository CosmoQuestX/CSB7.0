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
            <h3 class="font-weight-bold" style="display: inline;">Title Left (needs set in backend)</h3>
            <p>Content Left (needs set in backend) </p>

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
                    <h3 class="font-weight-bold">Hello, <?php echo $user['name'];?> !</h3>
                    <?php
                }
            ?>

            <div class="text-blk">
                <p>Content Welcome (needs set in Backend)</p>
            </div>

        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <?php //$txt = $lang['app_page']['text-boxes']['app-right']; ?>

        <div id="app-right" class="col-md-3 p-4">
            <h3 class="font-weight-bold">Title Right (needs set in backend)</h3>
            <div class="text-blk">
            <p>Content Right (needs set in backend)</p>

            </div>
        </div>


    </div>
</div>

<!-- p> Discord is the main forum where members of the CosmoQuest community come to do science and also hang out.
If you dont know what Discord is, think of it as a modern version of Internet Relay Chat. You create one account on discord.gg, and can use that one account to join multiple servers.
You have one username, but can make a nickname for each server. Each server is composed of many channels, some text channels like IRC but also voice channels where you can communicate in near real time with a microphone.
/p -->

