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
               on <a href="https://discord.gg/pVGXJDUKud">Discord</a> in the #suggestions channel. Want to help implement new features and refine the old? Pipe up in the
               #volunteers-reporting-for-duty channel.</p>
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


            <p> Welcome to Cosmoquest's new and improved Community Science portal, home of all of our current projects.
            It's been a long time since we've done one of these (Can you believe Bennu Mappers was the summer of 2020?).
            We are excited to bring it back for all of you, old and new. Before you can get started you will need to
            log in by clicking the login link in the top right corner of this page.
            If you had an account during our Bennu Mappers project or earlier, you will need to make a new one as
            unfortunately the old user list did not survive the database transfer. </p>

            <p> Please note that this software is still in beta and you may occasionally lose data such as marked images
            or other progress. Know that your contributions are valuable. We are sorry if (when) this happens and will
            try to fix it as soon as possible. </p>

            <p> Our current project is another version of Moon Mappers. Join us? </p>

        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <?php //$txt = $lang['app_page']['text-boxes']['app-right']; ?>

        <div id="app-right" class="col-md-3 p-4">
            <h3 class="font-weight-bold">Why the update?</h3>
            <p>Our Citizen Science Builder software previously utilized a software framework called $name. Overtime, we realized we were limited in what
               we could do by the rate of that frameworks updates, and if anyone wanted to contribute to this open-source platform, they
               were going to have to deal with our sphagetti code and framework... and that was a lot. At the end of Bennu Mappers, we started to
               rewrite of our platform. It will make it easier to contribute to and maintain. Also, it will be hopefully better for doing
               science. This is a work in progress, so expect regular changes to pop up. <a href="https://discord.gg/pVGXJDUKud">Join us on
               Discord to chat about changes. After joining go to #volunteers-reporting-for-duty and @ mention the mods role.
               Some lovely human will give you the @coders role.
               You will now have access to the  #coders-den channel, the hub for all discussion on this project. </a></p>
        </div>


    </div>
</div>
