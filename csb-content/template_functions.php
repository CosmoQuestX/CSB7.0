<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 7/17/19
 * Time: 12:02 PM
 */

function loadHeader() {
    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers, $page_title;
    $csb_headers  = "<link rel='stylesheet' type='text/css' href='". $BASE_URL ."csb-content/csb.css'>";


    require_once($THEME_DIR . "header.php");
}

function loadFooter() {
    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers, $page_title;

    require_once($THEME_DIR . "footer.php");
}

function loadMeta() {
    global $csb_headers;

    echo $csb_headers;
}

function loadUser() {
    global $BASE_URL, $user, $adminFlag;

    if ( $user === FALSE) {         // NOT LOGGED IN
        if ($adminFlag === FALSE) {
           ?> <button id="alert-botton">Login</button> <?php
        }
        else {
            echo "not logged in";
        }
    }
    else {                           // LOGGED IN
        echo "Hello, " . $user['name'];
        ?>
        <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
            <input type="submit" name="go" value="logout">
        </form>
        <?php
    }


}

function loadLoginBox() {
    global $BASE_URL;
    ?>

    <div id="alert-box" class="alert">

        <!-- Modal content -->
        <div class="alert-content">
            <span class="close">&times;</span>
            <div id="form-box">
                <H3>Please Login</H3>

                <form id="LoginForm" action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">

                    <input type="hidden" name="go" value="login">
                    <input type="hidden" name="referringURL" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>">

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

    </div>

    <?php
}