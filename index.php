<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */

/** ---------------------------------------------------------------------
 *  ---------------------------------------------------------------------
 *  This loads the public pages
 *
 *
 *
 *
 *
 *
 * ----------------------------------------------------------------------
 * ---------------------------------------------------------------------- **/

/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

    require_once("./csb-loader.php");
    require_once ($DB_class);
    require_once ($BASE_DIR."csb-admin/auth.php");


/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */

    echo "Made it this far.<br>";

    echo "let's do the thing.";

/* ----------------------------------------------------------------------
    Is the person logged in?
   ---------------------------------------------------------------------- */

    $db = new DB($db_servername, $db_username, $db_password, $db_name);

    if ($login || isLoggedIn($db) === FALSE) {         // NOT LOGGED IN
        require_once ($BASE_DIR."csb-content/templates/login.php");
    }


    ?>


<form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
    <input type="submit" name="go" value="logout">
</form>
