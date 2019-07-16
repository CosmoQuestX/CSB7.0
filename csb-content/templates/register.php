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

    $referringURL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


/* ----------------------------------------------------------------------
   Where did they come from
   ---------------------------------------------------------------------- */

    require_once ($THEME_DIR."/header.php");

?>

<!-----------------------------------------------------------------------
   Registration form
        1) Tell them if there is an error (check with JS) TODO
        2) Get their info
        3) Send their info and the return to URL in the form

        Also include:
            - signin instead TODO

   ---------------------------------------------------------------------->
    <form action="<?php echo($BASE_URL."csb-admin/auth-login.php"); ?>" method="post" id="form-login">

        <input type="hidden" name="referringURL" value="<?php echo $referringURL;?>">
        <input type="hidden" name="go" value="register">

        <div class="error-msg"></div>

        <div class="field-textbox">
                <label for="login">Username</label>
                <input name="name" type="text"><br/>
                <label for="email">Email</label>
                <input name="email" type="text"><br/>
                <label for="password">Confirm Password</label>
                <input name="password" type="password"><br/>
                <label for="confirm">Password</label>
                <input name="confirm" type="password"><br/>
        </div>

        <div class="fields-checkbox">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember-me">Remember me</label>
        </div>

        <div class="field-submit">
            <input type="submit" name="register" value="Register"
                   class="form-submit-button">
        </div>

</form>

<?php

require_once ($THEME_DIR."/footer.php");

?>
