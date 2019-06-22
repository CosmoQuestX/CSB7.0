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
   load things TODO Make logout function
   ---------------------------------------------------------------------- */

   else {
       require_once ("admin-templates.php");
       loadHeader($BASE_URL, $THEME_DIR, $auth);
       echo "I am here<br>";
       ?>
       <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
            <input type="submit" name="go" value="logout">
        </form>
        <?php
       loadFooter($THEME_DIR);
   }

   $db->closeDB();