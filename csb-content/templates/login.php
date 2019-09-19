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



<div class="container-fluid d-flex justify-content-center align-items-center mt-4">
    <div class="bg-white" style="width: 500px;">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-12 p-3" style="color:black;">
                
                

                <form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">

                    <input type="hidden" name="referringURL" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="go" value="login">

                    <div class="error-msg"></div>

                    <div class="field-textbox">
                        <label for="login">Username</label>
                        <input name="name" class="form-control" type="text"
                            value="<?php if (isset($_COOKIE["name"])) {
                                echo $_COOKIE["name"];
                            } ?>"
                        >
                        <label for="password">Password</label>
                        <input name="password" class="form-control" type="password">
                    </div>

                    <div class="fields-checkbox">
                        <input type="checkbox" name="remember" id="remember"
                            <?php if (isset($_COOKIE["member_login"])) { ?> checked
                            <?php } ?> />
                        <label for="remember-me">Remember me</label>
                    </div>

                    <div class="field-submit">
                        <input type="submit" class="btn btn-cq btn-block" name="login" value="Login"
                            class="form-submit-button">
                    </div>
                </form>

                <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
                    <input type="submit" class="btn btn-secondary btn-block" name="go" value="Register">
                </form>


                
                </div>
            </div>
        </div>
    </div>
</div>



<?php

require_once($THEME_DIR . "/footer.php");

?>
