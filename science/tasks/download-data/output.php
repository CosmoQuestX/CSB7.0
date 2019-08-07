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

require_once("../../../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

echo "mark_id\timage_name\tx\ty\tdiameter\ttype\tdetails\tdate\tuser\n";

// get the marks
$query = "SELECT id, image_id, x, y, diameter, type, details, image_user_id 
          FROM marks";
$results = $db->runQuery($query);

foreach ($results as $result) {
    echo $result['id'] . "\t" .
         $result['image_name'] . "\t" .
         $result['x'] . "\t" .
         $result['y'] . "\t" .
         $result['diameter'] . "\t" .
         $result['type'] . "\t" .
         $result['details'] . "\t" .
         $result['image_user_id'] . "\n";
}

?>