<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
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
            <h3 class="font-weight-bold">Bennu Mappers</h3>
            <ul>
                <li>View Leaderboard</li>
                <li>Provide a name to use in publications</li>
                <li>See who marked possible sample sites</li>
                <li>Learn what's next</li>
            </ul>
            <h3 class="font-weight-bold">Other Projects</h3>
            <ul>
                <li>Stay tuned...</li>
            </ul>
        </div>

        <!-- main block --------------------------------------------------------------- -->
        <div id="app-main" class="col-md-6 p-4">
            <?php
                if ( $user === FALSE) {         // NOT LOGGED IN
            ?>
            <h3 class="font-weight-bold">Welcome!</h3>
                <div class="center">
                    <a id="alert-botton-home" data-toggle="modal" data-target="#loginModal" class="btn-default" href="#">Login</a>
                </div>
            <?php
                }
                else {                           // LOGGED IN
                    ?>
                    <h3 class="font-weight-bold">Hello, <?php echo $user['name'];?> !</h3>
                    <?php
                }
            ?>


            <p> Intro Texxt</p>

        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <?php //$txt = $lang['app_page']['text-boxes']['app-right']; ?>

        <div id="app-right" class="col-md-3 p-4">
            <h3 class="font-weight-bold">Why the update?</h3>
            <p>right blurb</p>
        </div>


    </div>
</div>
