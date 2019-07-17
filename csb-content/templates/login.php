<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:44 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

/* ----------------------------------------------------------------------
   Where should they go to?
   ---------------------------------------------------------------------- */

// Are they on this site?

$referringURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


/* ----------------------------------------------------------------------
   Where did they come from
   ---------------------------------------------------------------------- */

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader();

?>

<!-----------------------------------------------------------------------
   Login form
        1) Tell them if there is an error (check with JS) TODO
        2) Get their info
        3) Send their info and the return to URL in the form

        Also include:
            - Link to registration TODO
            - Link to "Forgot Password" TODO

   ---------------------------------------------------------------------->

<div class="container">
    <div id="form-box">
        <form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">

            <input type="hidden" name="referringURL" value="<?php echo $referringURL; ?>">
            <input type="hidden" name="go" value="login">

            <div class="error-msg"></div>

            <div class="field-textbox">
                <label for="login">Username</label>
                <input name="name" type="text"
                       value="<?php if (isset($_COOKIE["name"])) {
                           echo $_COOKIE["name"];
                       } ?>"
                >
                <label for="password">Password</label>
                <input name="password" type="password">
            </div>

            <div class="fields-checkbox">
                <input type="checkbox" name="remember" id="remember"
                    <?php if (isset($_COOKIE["member_login"])) { ?> checked
                    <?php } ?> />
                <label for="remember-me">Remember me</label>
            </div>

            <div class="field-submit">
                <input type="submit" name="login" value="Login"
                       class="form-submit-button">
            </div>
        </form>

        <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
            <input type="submit" name="go" value="register">
        </form>
    </div>
</div>
<?php

require_once($THEME_DIR . "/footer.php");

?>
