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

require_once("../csb-loader.php");
require_once ($DB_class);
require_once ($BASE_DIR."csb-accounts/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);


/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title;

$page_title = "";

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader();
openMain();
?>
<h1>
    Register
</h1>

<form id="registration-form">
    <div id="form-input-box">
    <div id="form-input-row">
        <div id="form-input-left">Username</div>
        <div id="form-input-right"><input type="text" name="first_name" value="<?php echo $thisUser['first_name']; ?>"></div>
    </div>
    </div>
</form>

<?php
closeMain();
require_once($THEME_DIR . "/footer.php");


