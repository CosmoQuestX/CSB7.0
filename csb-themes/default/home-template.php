<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */

/** Get the setup files for the app dynamically TODO make this a function */
require_once ($BASE_DIR . "/csb-apps/Bennu/bennu-template.php");
$lang = $BASE_DIR . "csb-apps/Bennu/lang/bennu.en.json";

$lang = file_get_contents($lang);
$lang = json_decode($lang, true);

?>



<div id="main" class="container-fluid">
    <div class="row">

        <div id="app-left" class="col-md-2 d-none d-sm-block">
            <!-- LEFT -->

            <?php $txt = $lang['app_page']['text-boxes']['app-left']; ?>
            <h2><?php echo $txt['title']; ?></h2>
            <p> <?php echo $txt['fact1-title']; ?><br/>
                <?php echo $txt['fact1-content']; ?></p>
            <p> <?php echo $txt['fact2-title']; ?><br/>
                <?php echo $txt['fact2-content']; ?></p>
            <p> <?php echo $txt['fact3-title']; ?><br/>
                <?php echo $txt['fact3-content']; ?></p>
            <p> <?php echo $txt['completed']; ?><br/>
                 #####</p>
            <p><?php echo $txt['remaining']; ?><br/>
                 #####</p>
            <p><?php echo $txt['dueDate']; ?><br/>
               <?php echo $txt['dueDateValue'];?></p>
            <img class="app-spacecraft" src="<?php echo $BASE_URL.'csb-apps/Bennu/images/ORex-Transparent.png';?>">

        </div><!-- END LEFT -->




        <div id="app-main" class="col-md-8 main-dash d-none d-sm-block">
            <!-- MIDDLE -->


            <?php

                if ( $user === FALSE) {         // NOT LOGGED IN
                    ?><H3>Welcome!</H3>
                        <div class="center">
                            <a id="alert-botton-home" class="btn-default" href="#">Login</a>
                        </div>
                    <?php
                }
                else {                           // LOGGED IN
                ?>

                <h3>Hello, <?php echo $user['name'];?> !</h3>

                <div class="center">
                    <a class="btn-default" href="<?php echo $ADMIN_URL;?>profile.php">Profile</a>
                </div>

                <?php
                    }
                ?>


                <p> Bennu Mappers Phase 1 is over, but we're not done!</p>
                <p> You made more than 10 million rock measurements and
                    mapped the potential landing sites for the OSIRIS-REx
                    spacecraft. Phase 2 is coming for Hero Markers
                    (you know who you are). </p>
                <p> We're developing cool extras for all of you. Stay tuned!</p>



        </div><!-- END MIDDLE -->





        
        <div id="app-right" class="col-md-2 d-none d-sm-block">
            <!-- RIGHT -->

            <?php $txt = $lang['app_page']['text-boxes']['app-right']; ?>
            <h1><?php echo $txt['title'];?></h1>
            <p><?php echo $txt['blurb'];?></p>
            <p><?php echo $txt['footer'];?></p>
            
            <div class="btn-group w-100">
                <a href="#" class="btn btn-cq w-50">Discord</a>
                <a href="#" class="btn btn-cq w-50">Twitch</a>
            </div>
            
           <!-- <iframe src="https://titanembeds.com/embed/443490369443856384" height="245" width="350" frameborder="0"></iframe> -->

        </div><!-- END RIGHT -->





    
    </div>
</div>

<div class="container d-block d-sm-none bg-dark p-4">
    <div class="row">
        <div class="col-12">
        
            <h2>Hello!</h2>
            <p>Thank you for visiting Cosmoquest on mobile. If you would like to use the mapping web app please come back on a larger device.</p>
        
        </div>
    </div>
</div>

