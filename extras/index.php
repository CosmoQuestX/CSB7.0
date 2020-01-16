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

/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title;

$page_title = "";

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader();

?>
    <div id="main">
        <div class="container">

            <div id="" class="left-dash left">
                <?php

                $dir = getcwd() . "/tasks";
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
                if ($task !== NULL && file_exists($BASE_DIR . "extras/tasks/" . $task . "/" . $task . ".php")) {
                    echo "<h2>Task: " . $task . "</h2>";
                    require_once($BASE_DIR . "extras/tasks/" . $task . "/" . $task . ".php");
                } else {
                    error_log("Somebody tried to call the extras task {$task}");
                    echo "Select a task to do from the lefthand menu";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<?php
loadFooter();
