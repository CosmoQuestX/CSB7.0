<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */

/** This fucker will try and time out on you */
ini_set('max_execution_time', 300);
set_time_limit(0);
ignore_user_abort(1);

/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

require_once("../../../csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-admin/auth.php");

/* ----------------------------------------------------------------------
   Open the database because you're gonna need it
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);
$output = "";

// If page = 0, open a server-side temp file, write headers
if (isset($_GET)) {
	$page	= $_GET['page'];       
    		// setup the temp file to write
    		if ($file = fopen($BASE_DIR."temp/tempdownload.csv", a)) {
    		}
    		// throw an error if just won't happen
    		else {
        		$db->closeDB();
        		die("couldn't open temporary file on server");
    		}
	if ($page == 0) $output .= "mark_id\timage_name\tx\ty\tdiameter\ttype\tdetails\tdate\tuser\n";
	else if ($page == 3) die("good enough");

}
$start = $page * 10000;

// 2) Page through the DB 10,000 marks at a time, and write them to that file
$query = "SELECT marks.id, image_sets.name as image_name, marks.x, marks.y, marks.diameter, marks.type, marks.details, images.details as origin, users.name, marks.created_at 
FROM marks, images, image_sets, users
WHERE marks.type = 'boulder' AND marks.image_id = images.id AND images.image_set_id = image_sets.id AND marks.user_id = users.id
LIMIT 0,10000";
$results = $db->runQuery($query);

foreach ($results as $result) {

    $output .=  $result['id'] . "\t" .
                $result['image_name'] . "\t" .
                $result['x'] . "\t" .
                $result['y'] . "\t" .
                $result['diameter'] . "\t" .
                $result['type'] . "\t" .
                $result['details'] . "\t" .
                $result['origin'] . "\t" .
                $result['created_at'] . "\t" .
                $result['name'] . "\n";
}

// 3) When you get to the last page, output temp file to web

// after the if statements in the paging, write to file
fwrite($file, $output);
fclose($file);

$page++;
$url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?app_id=21&combined=FALSE&page=".$page;
echo $url;
    ?>
    <html>
    <head>
        <meta http-equiv="refresh" content="0;URL=<?php echo $url;?>" />
    </head>
    </html>
