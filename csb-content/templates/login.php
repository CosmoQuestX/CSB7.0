<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:44 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header("Location: http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST']);
    exit();
}

/* This function duplicates including csb-content/themes/login.php
 * Keep in mind that if you chance something here you need to change
 * it there as well without breaking anything.
 */

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
    <div id="form-input-box">
        <form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">

            <input type="hidden" name="referringURL" value="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
            <input type="hidden" name="go" value="login">

			<div id="form-input-row">
            	<div id="error-msg"><?php if(isset($_SESSION['errmsg'])) { echo "<span style=\"color: red;\">" . $_SESSION['errmsg'] ."</span>"; unset($_SESSION['errmsg']); } ?></div>
			</div>
			
            <div id="form-input-row">
                <div id="form-input-left"><label for="login">Username</label></div>
                <div id="form-input-right"><input name="name" type="text" value="<?php if (isset($_COOKIE["name"])) { echo $_COOKIE["name"]; } ?>"></div>
            </div>
            <div id="form-input-row">
                <div id="form-input-left"><label for="password">Password</label></div>
                <div id="form-input-right"><input name="password" type="password"></div>
            </div>

            <div id="form-input-row">
                <input type="checkbox" name="remember" id="remember"<?php if (isset($_COOKIE["member_login"])) { echo " checked"; } ?>/>
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
</div>
<?php

require_once($THEME_DIR . "/footer.php");

?>
