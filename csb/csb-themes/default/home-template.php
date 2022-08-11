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
            <h3 class="font-weight-bold">Pardon the Dust</h3>
            <p>As new tools come online, we will link to them here!</p><p>Have a feature you really want to see? Let us know
               on <a href="https://discord.gg/6xVynhMqTZ">Discord</a> in the #suggestions channel. Want to help implement new features and refine the old? Pipe up in the
               #volunteers-reporting-for-duty channel.</p>
        </div>

        <!-- main block --------------------------------------------------------------- -->
        <div id="app-main" class="col-md-6 p-4">
            <?php
                if ( $user === FALSE) {         // NOT LOGGED IN
            ?>
            <h3 class="font-weight-bold">Welcome!</h3>
                <div class="center">
                    <a id="alert-button-home" data-toggle="modal" data-target="#loginModal" class="btn-default" href="#">
                       <span class="btn-default">Login</span></a>
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
            <p>Our Citizen Science Builder software used to utilize a software framework. Overtime, we realized we were limited in what
               we could do by the rate of that frameworks updates, and if anyone wanted to contribute to this opensource plateform, they
               were going to have to deal with our code and framework... and that was a lot. At the end of Bennu Mappers, we started
               rewrite of our plateform that should make it easier to contribute to, easier to maintain, and hopefully better for doing
               science. This is a work in progress, so expect regular changes to pop up. <a href="https://discord.gg/6xVynhMqTZ">Join us on
               Discord to chat about changes.</a></p>
        </div>


    </div>
</div>
