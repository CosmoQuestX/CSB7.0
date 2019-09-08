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
    /* This function duplicates including csb-content/themes/login.php 
     * Keep in mind that if you chance something here you need to change
     * it there as well without breaking anything.
     */
    global $BASE_URL;
    ?>

    <div id="alert-box" class="alert">

        <!-- Modal content -->
        <div class="alert-content">
            <span class="close">&times;</span>

		    <div id="form-box">
		    <div id="form-input-box">
      		      <H3>Please Login</H3>
        	<form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">
            <input type="hidden" name="referringURL" value="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
            <input type="hidden" name="go" value="login">

				<div id="form-input-row">
    	        	<div class="error-msg"><?php if(isset($_SESSION['errmsg'])) { $_SESSION['showmodal']=TRUE; echo "<span style=\"color: red;\">" . $_SESSION['errmsg'] ."</span>"; } ?></div>
				</div>
	   	        <div id="form-input-row">
    	            <div id="form-input-left"><label for="login">Username</label></div><div id="form-input-right"><input name="name" type="text" value="<?php if (isset($_COOKIE["name"])) { echo $_COOKIE["name"]; } ?>"></div>
	            </div>
    	        <div id="form-input-row">
        	        <div id="form-input-left"><label for="password">Password</label></div><div id="form-input-right"><input name="password" type="password"></div>
	            </div>
	            <div id="form-input-row">
 	               <input type="checkbox" name="remember" id="remember"<?php if (isset($_COOKIE["member_login"])) { echo " checked"; } ?>/><label for="remember-me">Remember me</label>
        	    </div>
	            <div class="field-submit"><input type="submit" name="login" value="Login" class="form-submit-button"></div>
                
            </form>
        <form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="get" id="form-logout">
            <input type="submit" name="go" value="register">
        </form>
	</div>
    </div>
	</div>
    </div>

    <?php

}
        