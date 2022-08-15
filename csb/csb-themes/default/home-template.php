<?php
/**
 * Created by PhpStorm.
 * User: starstryder/EMadaus
 * Date: 8/15/22
 * Time: 15:51
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
            <h3 class="font-weight-bold">Pardon our Dust</h3>
            <p>As new Community Science tools come online, we will link to them here!</p><p> This page will be updated frequently, check back soon for more. Have a feature you really want to see? Let us know
               on our <a href="https://discord.gg/pVGXJDUKud">Discord</a> in the #suggestions channel.
               Want to help implement new features and refine the old ones? Let us know in the #volunteers-reporting-for-duty channel.</p>

            <p>Need inspiration? Catch us live on <a href="twitch.tv/Cosmoquestx">Twitch</a>. Astronomy Cast is on Mondays at 1800 UTC.
               The rest of the week is Daily Space at 1600 UTC. If we're not live you can check out our <a href="twitch.tv/cosmoquestx/videos">past broadcasts</a> up to 30 days after the show.
               </p>
               <img src="/csb/csb-content/images/Pardon_Our_Dust.png" alt="All black clip art of a hammer" width="64" height="64">
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
                <p> Welcome to Cosmoquest's new and improved Community Science portal, home of all of our current projects. It will contain tools for both our own Community Science projects and tools to make your own.
                It's been a long time since we've done one of these (Can you believe that Bennu Mappers was the summer of 2020?).
                We are excited to bring it back for all of you, old and new. Before you can get started you will need to create a new user account by clicking the login link at the top of this page.
                You can also get to the login by clicking the big red button right above this paragraph and selecting register.
               </p>
               <p> If you had an account during the Bennu Mappers project or earlier, you will need to make a new one as
                unfortunately the old user list did not survive the database transfer. If you have already created a new account you can simply log in.</p>

                <p> Please note that this software is still in beta and you may occasionally lose data such as marked images
                or other progress. Know that your contributions are valuable. We are sorry if (when) this happens and will
                try to fix it as soon as possible. </p>

                <p> Our current Community Science project is another version of Moon Mappers. Join us? </p>
            </div>

        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <?php //$txt = $lang['app_page']['text-boxes']['app-right']; ?>

        <div id="app-right" class="col-md-3 p-4">
            <h3 class="font-weight-bold">Why the update?</h3>
            <div class="text-blk">
            <p>Our Citizen Science Builder software previously utilized a software framework called $Name. Overtime, we realized we were limited in what
               we could do by the rate of that frameworks updates, and if anyone wanted to contribute to this open-source platform, they
               were going to have to deal with our spaghetti code and framework... and that was a bit much. At the end of the Bennu Mappers project, we started a
               rewrite of our platform. Our goals are to make it easier for you to contribute and for us to maintain. Also, it will be hopefully better for doing
               science. This is still a work in progress, so expect regular changes to pop up. <a href="https://discord.gg/pVGXJDUKud"> Join us on
               Discord </a> to chat about changes.</p>

            </div>
        </div>


    </div>
</div>