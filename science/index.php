<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */

/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

require_once("../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-account/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);

// if $login isn't set, set it to avoid a PHP notice.
if (!isset($login)) {
    $login = FALSE;
}

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader();

if (filter_var($login, FILTER_VALIDATE_BOOLEAN) || $user === FALSE) { // NOT LOGGED IN
    echo "Login Required"; // TODO open login alert
} /* ----------------------------------------------------------------------
   Do they have the correct role?
   ---------------------------------------------------------------------- */

elseif ($_SESSION['roles'] != $CQ_ROLES['SITE_SCIENTIST'] && $_SESSION['roles'] != $CQ_ROLES['SITE_ADMIN'] && $_SESSION['roles'] != $CQ_ROLES['SITE_SUPERADMIN']) {
    // TODO be a bit politer when rejecting nosy users
    die("ERROR: You don't have permission to be here");
} /* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */

else { // they clearly have permissions
    global $page_title;

    $page_title = "";

    ?>
    <div id="main">
        <div class="container">

            <div id="" class="left-dash left">
                <?php

                $dir = $BASE_DIR . "/science/tasks";
                $listings = array_diff(scandir($dir), array('..', '.'));
                ?>

                <h3>Options</h3>


                <?php
                foreach ($listings as $item) { ?>
                    <a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?task=<?php echo $item; ?>"><?php echo $item; ?></a>
                    <br/>
                    <?php
                }

                ?>
            </div>

            <div class="main-dash right">
                <?php
                // Is a value set?  Check if task exists. If yes, execute. Else, instructions!
                $task = basename(filter_input(INPUT_GET, 'task', FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0));
                if ($task !== NULL && file_exists($BASE_DIR . "science/tasks/" . $task . "/" . $task . ".php")) {
                    echo "<h2>Task: " . $task . "</h2>";
                    require_once($BASE_DIR . "science/tasks/" . $task . "/" . $task . ".php");
                } else {
                    error_log("Somebody tried to call the science task {$task}");
                    echo "Select a task to do from the lefthand menu";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php
}
loadFooter();