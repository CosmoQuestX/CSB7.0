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

    require_once($BASE_DIR . "/csb-content/template_functions.php");
    loadHeader();

    /* ---------------------------------------------------------------------
     The default header shows the login div id user on the top right.
     So since we definitely don't have a user yet, hide the div.
     --------------------------------------------------------------------- */
    
    echo "<script type=\"text/javascript\" language=\"JavaScript\">";
    echo "document.getElementById(\"user\").style.display = \"none\";";
    echo "</script>\n";
?>

<!-----------------------------------------------------------------------
   Registration form
        1) Tell them if there is an error (check with JS) TODO
        2) Get their info
        3) Send their info and the return to URL in the form

        Also include:
            - signin instead TODO

   ---------------------------------------------------------------------->

   
   
<div class="container">
    <div id="form-box">
    <div id="form-input-box">
    <form action="<?php echo($BASE_URL."csb-accounts/auth-login.php"); ?>" method="post" id="form-login">

        <input type="hidden" name="referringURL" value="<?php echo $referringURL;?>">
        <input type="hidden" name="go" value="regForm">

	<div id="form-input-row">
	        <div class="error-msg"><?php if(isset($_SESSION['regmsg'])) { echo "<span style=\"color: red;\">" . $_SESSION['regmsg'] ."</span>"; unset($_SESSION['regmsg']); } ?></div>
	</div>
	
    <div id="form-input-row">
		<div id="form-input-left"><label for="name">Username</label></div>
		<div id="form-input-right"><input name="name" type="text"></div>
	</div>                
    <div id="form-input-row">
		<div id="form-input-left"><label for="email">Email</label></div>
		<div id="form-input-right"><input name="email" type="text"></div>
	</div>                
    <div id="form-input-row">
		<div id="form-input-left"><label for="password">Enter Password</label></div>
		<div id="form-input-right"><input name="password" type="password"></div>
	</div>		
    <div id="form-input-row">
		<div id="form-input-left"><label for="confirm">Confirm Password</label></div>
		<div id="form-input-right"><input name="confirm" type="password"></div>
	</div>                

    <div id="form-input-row">
        <div class="fields-checkbox">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember-me">Remember me</label>
        </div>
	</div>
        <div class="field-submit">
            <input type="submit" name="register" value="Register"
                   class="form-submit-button">
        </div>

	</form>
	</div>
	</div>
</div>
<?php

require_once ($THEME_DIR."/footer.php");

?>
