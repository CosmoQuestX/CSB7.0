<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 4:21 PM
 */

/* ----------------------------------------------------------------------
   Load all needed includes
   ---------------------------------------------------------------------- */
require_once ("../csb-loader.php");
require_once ($DB_class);
require_once ($BASE_DIR."csb-admin/auth.php");
$adminFlag = 1;

/* ----------------------------------------------------------------------
   Check for post variables
   ---------------------------------------------------------------------- */
$login = FALSE;
$reg = FALSE;
if(isset($_POST) && !empty($_POST)) {
    if (isset($_POST['go']) && $_POST['go'] == 'login') $login = TRUE;
    elseif (isset($_POST['go']) && $_POST['go'] == 'reg') $reg = TRUE;
}

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);


if ($login || $user === FALSE ) { // NOT LOGGED IN
    require_once ($BASE_DIR."csb-content/templates/login.php");
}

/* ----------------------------------------------------------------------
    Are they trying to register?
   ---------------------------------------------------------------------- */

elseif ($reg) {
    require_once ($BASE_DIR."csb-content/templates/login.php");
}

/* ----------------------------------------------------------------------
   load things
   ---------------------------------------------------------------------- */

else {
    global $page_title;

    $page_title = "Profile & Settings";

    require_once($BASE_DIR . "/csb-content/template_functions.php");
    require_once("admin-dashboards.php");

    loadHeader();

/* ----------------------------------------------------------------------
    are they trying to save something they input?
   ---------------------------------------------------------------------- */

    if(isset($_POST) && !empty($_POST)) {

        if (isset($_POST['email'])) {
            $query = "update users set email = ?";
            $params[] = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);
            $params_type = "s";


            if (isset($_POST['first_name'])) {
                $query .= ", first_name = ?";
                $params[] = preg_replace("/;/","",filter_input(INPUT_POST,'first_name',FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                $params_type .="s";
            }

            if (isset($_POST['last_name'])) {
                $query .= ", last_name = ?";
                $params[] = preg_replace("/;/","",filter_input(INPUT_POST,'last_name',FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                $params_type .="s";
            }

            if (isset($_POST['public_name'])) {
                $query .= ", public_name = 1";
            } else {
                $query .= ", public_name = 0";
            }

            $query       .= " where id = ?";
            $params[]     = filter_var($_SESSION['user_id'],FILTER_SANITIZE_NUMBER_INT);
            $params_type .= "s";

        } else {
            echo "email address required";
        }
        if($db->update($query, $params_type, $params)) {
            $saved = TRUE;
        }
        else {
            $saved = FALSE;
        }
    }


// Go back to loading the page

    $thisUser = $db->getUser(filter_var($_SESSION['user_id'],FILTER_SANITIZE_NUMBER_INT));
    ?>

    <div id="main">
        <div class="container">

            <div id="" class="left-dash left">
                Things to do will go here
            </div>

            <div class="main-dash right">
                <img class="right" src="<?php echo $IMAGES_URL;?>Profile/Default_Avatar.png">
                <h3>
                    Welcome, <?php echo $user['name']; ?>
                </h3>
                <p>
                    <strong> Account Settings </strong><br/>
                <span class="instructions">Your privacy matters! Our team programmers do have access to this
                    information, but the only thing that can be publicly seen is your username. We will,
                    with permission only, use your first and last name to give you credit for things
                    you accomplish.</span></p>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
				<div id="form-input-box">
        			<div id="form-input-row">
        				<div id="form-input-left">First Name</div>
        				<div id="form-input-right"><input type="text" name="first_name" value="<?php echo $thisUser['first_name']; ?>"></div>
        			</div>
        			<div id="form-input-row">
        				<div id="form-input-left">Last Name</div>
        				<div id="form-input-right"><input type="text" name="last_name" value="<?php echo $thisUser['last_name']; ?>"></div>
        			</div>                
        			<div id="form-input-row">
        				<div id="form-input-left">Email</div>
        				<div id="form-input-right"><input type="text" name="email" value="<?php echo $thisUser['email']; ?>"></div>
        			</div>
        			<div id="form-input-row">
        				<div id="form-input-left">Change your password?</div>
        				<div id="form-input-right"></div>
        			</div>
        				
					<input type="checkbox" name="public_name"<?php if ($thisUser['public_name'] == 1) echo " checked"?>> Do we have permission to publish your name with science results? 
	       			</div>                

                    <input type="submit" value="Save Settings" class="btn-default right">
                </form>
                <?php 
                    if (isset($saved) && $saved) { 
                        echo "<span class='red'>Settings saved!</span>"; unset($saved); 
                    }
                    elseif (isset($saved) && !$saved) {
                        echo "<span class='red'>Error saving settings!</span>"; unset($saved);
                    }
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>



    <?php
    loadFooter();
}

$db->closeDB();