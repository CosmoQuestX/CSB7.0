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
if(isset($_POST) && !empty($_POST)) {
    $login = FALSE;
    $reg = FALSE;
    if ($_POST['go'] == 'login') $login = TRUE;
    elseif ($_POST['go'] == 'reg') $reg = TRUE;
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

    if(isset($_GET) && !empty($_GET)) {

        if (isset($_GET['email'])) {
            $query = "update users set email = ?";
            $params = array($_GET['email']);
            $params_type = "s";


            if (isset($_GET['first_name'])) {
                $query .= ", first_name = ?";
                $params [] = $_GET['first_name'];
                $params_type .="s";
            }

            if (isset($_GET['last_name'])) {
                $query .= ", last_name = ?";
                $params [] = $_GET['last_name'];
                $params_type .="s";
            }

            if (isset($_GET['public_name'])) {
                $query .= ", public_name = 1";
            } else {
                $query .= ", public_name = 0";
            }

            $query       .= " where id = ?";
            $params[]     = $_SESSION['user_id'];
            $params_type .= "s";

        } else {
            echo "email address required";
        }
        echo $db->update($query, $params_type, $params);
    }


// Go back to loading the page

    $thisUser = $db->getUser($_SESSION['user_id']);
    ?>



    <div id="main" class="container">
        <div class="row">

            <div class="col-md-3 left-dash">

                <!-- LEFT DASH -->

                <div class="d-flex justify-content-center mb-3">
                    <img src="<?php echo $IMAGES_URL;?>Profile/Default_Avatar.png">
                </div>
                
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Account Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Change Your Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>

            </div>

            <div class="col-md-9 main-dash">
            
                <!-- MAIN DASH -->

                <h2 class="float-left"><?php echo $thisUser['name']; ?>'s Account Settings</h2>
                
                
                <br><br>
                
                
                <form action="profile.php" method="get">

                    
                    
                    <div class="form-group float-left w-50 pr-2">
                        <label>First Name:</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo $thisUser['first_name']; ?>">
                    </div>

                    <div class="form-group float-left w-50 pl-2">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo $thisUser['last_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $thisUser['email']; ?>">
                    </div>
                    
                    

                    <input type="checkbox" name="public_name"<?php if ($thisUser['public_name'] == 1) echo "checked"?>>
                    Do we have permission to publish your name with science results?

                    <input type="submit" value="Save Settings" class="btn btn-secondary btn-block mt-4 mb-2">

                </form>


                <p class="instructions">Your privacy matters! Our team programmers do have access to this
                    information, but the only thing that can be publicly seen is your username. We will,
                    with permission only, use your first and last name to give you credit for things
                    you accomplish.</p>
            

            </div>

        </div>
    </div>



    <?php
    loadFooter();
}

$db->closeDB();