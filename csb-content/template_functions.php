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
           ?> <button id="alert-botton">Login</button> <?php  // TODO ADD LOGIN BOX THAT WILL OPEN OVER SCREEN
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