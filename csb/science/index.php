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
require_once($ACC_DIR . "auth.php");


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


if (filter_var($login, FILTER_VALIDATE_BOOLEAN) || $user === FALSE) { // NOT LOGGED IN
    echo "Login Required"; // TODO open login alert
    exit();

} /* ----------------------------------------------------------------------
   Do they have the correct role?
   ---------------------------------------------------------------------- */

elseif (!userHasRole($CQ_ROLES['SITE_SCIENTIST'],$CQ_ROLES['SITE_PROJECTLEAD'], $CQ_ROLES['SITE_ADMIN'],$CQ_ROLES['SITE_SUPERADMIN'])) {
    header("Location: " . $BASE_URL . "error/error.php?error=403");
    exit();

} /* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */

else { // they clearly have permissions
    global $page_title;

    $dir = $BASE_DIR . "/science/tasks";
    $listings = array_diff(scandir($dir), array('..', '.'));

    $page_title = "science";


    $left = "<h3>Options</h3>\n";
    foreach ($listings as $item) {
        $left .= "<a href='" . $_SERVER['SCRIPT_NAME'] . "?task=$item'>$item</a><br/>";
    }

    // Is a value set?  Check if task exists. If yes, execute. Else, instructions!
    $task = basename(filter_input(INPUT_GET, 'task', FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0));
    if ($task !== NULL && file_exists($BASE_DIR . "science/tasks/" . $task . "/" . $task . ".php")) {
        $main = "<h2>Task: " . $task . "</h2>";
        require_once($BASE_DIR . "science/tasks/" . $task . "/" . $task . ".php");
    } else {
        error_log("Somebody tried to call the science task {$task}");
        $main =  "Select a task to do from the lefthand menu";
    }

}

require_once($BASE_DIR . "/csb-content/template_functions.php");

loadHeader();
load3Col($left, $main, "More Stuff");
loadFooter();
