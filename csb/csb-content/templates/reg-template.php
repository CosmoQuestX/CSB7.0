<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:44 PM
 */


// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader;
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


/* ----------------------------------------------------------------------
   Where should they go to?
   ---------------------------------------------------------------------- */

// Are they on this site?
$referringURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



/* -----------------------------------------------------------------------
   Registration form
        1) Tell them if there is an error (check with JS) TODO
        2) Get their info
        3) Send their info and the return to URL in the form

        Also include:
            - signin instead TODO

   ---------------------------------------------------------------------- */
GLOBAL $ACC_URL;

?>

<h3 class="font-weight-bold">Register</h3>
<div id="form-input-box">
    <form action="<?php echo($ACC_URL."auth-login.php"); ?>" method="post">
        <input type="hidden" name="referringURL" value="<?php echo $BASE_URL; ?>">
        <input type="hidden" name="go" value="regForm">

        <div class="error-msg"><?php if (isset($_SESSION['errMsg'])) {
                echo "<span style=\"color: red;\">" . $_SESSION['errMsg'] . "</span>";
                unset($_SESSION['errMsg']);
            } ?>
        </div>

        <div class="form-input-row">
            <div class="form-input-left"><label for="name">Username</label></div>
            <div class="form-input-right"><input name="name" type="text"></div>
        </div>

        <div class="clear"></div>
        <div class="form-input-row">
            <div class="form-input-left"><label for="email">Email</label></div>
            <div class="form-input-right"><input name="email" type="text"></div>
        </div>
        <div class="clear"></div>
        <div class="form-input-row">
            <div class="form-input-left"><label for="password">Enter Password</label></div>
            <div class="form-input-right"><input name="password" type="password"></div>
        </div>
        <div class="clear"></div>
        <div class="form-input-row">
            <div class="form-input-left"><label for="confirm">Confirm Password</label></div>
            <div class="form-input-right"><input name="confirm" type="password"></div>
        </div>
        <div class="clear"></div>
        <div class="form-input-row">
            <div class="fields-checkbox">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember-me">Remember me</label>
            </div>
        </div>
        <div class="clear"></div>
        <div class="field-submit">
            <input type="submit" name="register" value="Register"
                   class="form-submit-button">
        </div>
    </form>
</div>

<?php /*

 */ ?>



