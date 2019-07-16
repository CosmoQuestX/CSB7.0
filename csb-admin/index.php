<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
   require_once ("../csb-loader.php");
   require_once ($DB_class);
   require_once ($BASE_DIR."csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Check for post variables
   ---------------------------------------------------------------------- */
    if(isset($_POST) && !empty($_POST)) {
        $login = FALSE;
        $reg = FALSE;
        if ($_POST['go'] == 'login') $login = TRUE;
        elseif ($_POST['go'] == 'reg') $reg = TRUE;
    }


/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

   $db = new DB($db_servername, $db_username, $db_password, $db_name);

   if ($login || isLoggedIn($db) === FALSE) {         // NOT LOGGED IN
       require_once ($BASE_DIR."csb-content/templates/login.php");
   }

/* ----------------------------------------------------------------------
    Are they trying to register?
   ---------------------------------------------------------------------- */

    elseif ($reg) {
        require_once ($BASE_DIR."csb-content/templates/login.php");
    }


/* ----------------------------------------------------------------------
   Do they have the correct role?
   ---------------------------------------------------------------------- */

   elseif ($_SESSION['roles'] !== 1) {

        die("ERROR: You don't have permission to be here");
   }


/* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

   else {
       require_once ("admin-templates.php");
       require_once("admin-dashboards.php");

       loadHeader($BASE_URL, $THEME_DIR, $THEME_URL, "CSB Administration Dashboards", $auth);
       ?>

       <div id="main">
           <div class="container">

               <div id="" class="left-dash left">
                   <?php listDashboards(); ?>
               </div>

               <div class="main-dash right">

                   <h3>
                       Current Status
                   </h3>
                   <p>
                       Stuff will go here
                   </p>

                   <h3>
                       Current Stats
                   </h3>
                   <p>
                       Stats will go here
                   </p>
               </div>
               <div class="clear"></div>
           </div>
       </div>



        <?php
       loadFooter($THEME_DIR);
   }

   $db->closeDB();