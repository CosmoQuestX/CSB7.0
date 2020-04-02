<?php
/**
 * Error file
 */

// We need our basic configuration first.
require_once '../csb-loader.php';
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

// Also, we should try to get the referring URL if possible, I guess?
if(isset($_SESSION['referringURL'])) {
    $referrer=filter_var($_SESSION['refererringURL'],FILTER_SANITIZE_URL,0);
}

// Check if the person is logged in
$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);

// Now let's see which error we have been called with
$error=filter_input(INPUT_GET, 'error', FILTER_SANITIZE_NUMBER_INT);

// Load the view
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

loadHeader($page_title);

// Let's make sure we output an image that exists. Now let's hope that at least
// the fallback image exists. 
if(file_exists($THEME_DIR."images/".$error.".png")) {
    $img = $THEME_URL."images/".$error.".png";
}
else {
    $img = $THEME_URL."images/error.png";
}

?>
<div id='container-md'>
	<div class='row'>
        <div class="center">
        	<div id='app-error' class="col-md">
        		<img src='<?php echo $img; ?>' alt='Error image for error <?php echo $error?>.'>
        		<div id='app-error-btn' class="center">
        		<?php if(isset($referrer)) {
        		?>
        		<a id="alert-button-back" class="btn btn-primary" href="<?php echo $referrer; ?>">Go back</a>
        		<?php 
        		}
        		?>
        		<a id="alert-button-home" class="btn btn-primary" href="<?php echo $BASE_URL; ?>">Go to the home page</a>
        		</div>
        	</div>
        </div>
	</div>
</div>

<?php 
loadFooter();
// Only one header please

?>
