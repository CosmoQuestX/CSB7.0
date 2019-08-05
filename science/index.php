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
require_once ($DB_class);
require_once ($BASE_DIR."csb-admin/auth.php");

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

                   $dir = $BASE_DIR . "/science/tasks";
                   $listings = array_diff(scandir($dir), array('..', '.'));
                   ?>

                   <h3>Options</h3>
                   <ul>

                       <?php
                       foreach ($listings as $item) { ?>
                           <form id='<?php echo $item;?>' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='GET'>
                           <input type='hidden' name='task' value='<?php echo $item;?>'>
                               <li>
                                    <a href='#' onclick='document.getElementById("<?php echo $item;?>").submit();'>
                                        <?php echo $item; ?>
                                    </a>
                               </li>
                               <?php
                       }

                   ?>
               </div>

               <div class="main-dash right">
                    <?php
                    // Is a value set?  Do something! Else, instructions
                    if (isset($_GET['task'])) { // TODO ADD ERROR CHECKING
                        echo "<h2>Task: ".$_GET['task']."</h2>";
                        require_once("./tasks/".$_GET['task']."/".$_GET['task'].".php");
                    } else {
                        echo "Select a task to do from the lefthand menu";
                    }
                    ?>
               </div>
               <div class="clear"></div>
           </div>
       </div>
<?php
loadFooter();