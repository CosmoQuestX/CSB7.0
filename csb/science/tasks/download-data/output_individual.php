<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */

// This file should be run from the commandline, so make sure it is 

if (PHP_SAPI != "cli") {
    exit("Please run this from the command line");
}


// Objectives:
// - Download requested data into a file
// - Email user a download link when the file is ready
// TODO write a cron job to clear these out periodically


/* ----------------------------------------------------------------------
   check command line inputs: app_id, startDate, email
   ---------------------------------------------------------------------- */

if ($argc != 5) {
    error_log("incorrect number of arguments given. Expected 5, received" . $argc);

    die("missing inputs");
}

$app_id = $argv['1'];

// Does it have the data set up?
// Check if it's heroes or dates
$data = $argv['2'];
if ($data == "heroes") {
    $hero = TRUE;
} else {
    $hero = FALSE;
    $dateStart = $data;
    $dateEnd = date("Y-m-d", strtotime("$dateStart +7 day"));
}

$email_to = $argv['3'];

$download_id = $argv['4'];

/* ----------------------------------------------------------------------
   Get the settings
   ---------------------------------------------------------------------- */

//cut off "science/tasks/download-data/output_individual.php";
$dir = substr(getcwd(), 0, -7);
require_once($dir . "csb-loader.php");
require_once($DB_class);
require_once($email_class);

/* ----------------------------------------------------------------------
   Open the database because you're gonna need it
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

/* ----------------------------------------------------------------------
   Open the file
   ---------------------------------------------------------------------- */

// Setup the Filename TODO make this file name less stupid
if ($hero) {
    $filename = "DataFor_app" . $app_id . '_heroes.' . date("YmdHis") . ".csv";
} else {
    $filename = "DataFor_app" . $app_id . '_week' . $dateStart . '.' . date("YmdHis") . ".csv";
}
$filepath = $BASE_DIR . "temp/" . $filename;
$fileURL = $BASE_URL . "temp/" . $filename;

// Open the file & save name to db for downloads
if ($file = fopen($filepath, 'a')) {
    $output = "mark_id\timage_name\tx\ty\tdiameter\ttype\tdetails\tdate\tuser\n";
    $query = "UPDATE data_downloads 
                  SET link = '$fileURL', name = '$filename' 
                  WHERE id = $download_id";
    $db->runQuery($query);
} // throw an error if just won't open
else {
    $db->closeDB();
    error_log("couldn't open temporary file on server");
}

// Get the number of lines
if ($hero) {
    // get user_ids for users with more than 500 images marked
    // get marks from those folks that have 500 images

    $where = "WHERE user_id in 
                  (SELECT user_id 
                  FROM image_users WHERE image_id > 41228380 
                  GROUP BY user_id having count(distinct image_id) >= 500)";

} else {
    $where = "WHERE created_at >= '$dateStart'
              AND created_at < '$dateEnd'";
}

$numRows = $db->getNumRows('marks', $where);

echo "numRows: " . $numRows;

$lastPage = intval($numRows / 10000.0);
if (($numRows % 10000) != 0) $lastPage++; // round up to get the last page

/* ----------------------------------------------------------------------
    HERO DATA

    Get things one hero at a time.
   ---------------------------------------------------------------------- */

if ($hero) {

    // Get the Heros
    $query = "SELECT image_users.user_id, users.name 
                  FROM image_users, users 
                  WHERE image_users.image_id > 41228380 AND image_users.user_id = users.id
                  GROUP BY image_users.user_id having count(distinct image_users.image_id) >= 1000";
    $heroes = $db->runQuery($query);

    foreach ($heroes as $hero) {
        echo $hero['name'];
        $start = 0;
        $query = "SELECT marks.id, 
                 image_sets.name as image_name, 
                 marks.x, marks.y, marks.diameter, marks.type, marks.details, 
                 images.details as origin, 
                 users.name, marks.created_at 
                FROM marks, images, image_sets, users
                WHERE marks.user_id = " . $hero['user_id'] . " AND 
                 marks.image_id = images.id AND 
                 images.image_set_id = image_sets.id AND 
                 marks.user_id = users.id 
                LIMIT $start, 10000";
        $results = $db->runQuery($query);

        // Go through each of the 10000 or so lines - last batch likely less than 10000
        while ($results != FALSE) {
            foreach ($results as $result) {
                // 1) The mark is offset from mosaic. Get that offset
                $origin = json_decode($result['origin'], TRUE);

                // 2) Correct the x, y position
                $x = $result['x'] + $origin['x'];
                $y = $result['y'] + $origin['y'];

                if ($result['details'] != 'null') {
                    $details = json_decode($result['details'], TRUE);   // Details about marks (not always present)

                    // only update things if there is an offset
                    if ($origin['x'] != 0 || $origin['y'] != 0) {
                        $details['points'][0]['x'] += $origin['x'];
                        $details['points'][0]['y'] += $origin['y'];
                        $details['points'][1]['x'] += $origin['x'];
                        $details['points'][1]['y'] += $origin['y'];
                    }

                    $details_json = json_encode($details);
                } else {
                    $details_json = "null";
                }

                $output .= $result['id'] . "\t" .
                    $result['image_name'] . "\t" .
                    $x . "\t" .
                    $y . "\t" .
                    $result['diameter'] . "\t" .
                    $result['type'] . "\t" .
                    $details_json . "\t" .
                    $result['created_at'] . "\t" .
                    $result['name'] . "\n";
            }

            fwrite($file, $output);
            $output = "";

            $start += 10000;
            $query = "SELECT marks.id, 
                 image_sets.name as image_name, 
                 marks.x, marks.y, marks.diameter, marks.type, marks.details, 
                 images.details as origin, 
                 users.name, marks.created_at 
                FROM marks, images, image_sets, users
                WHERE marks.user_id = " . $hero['user_id'] . " AND 
                 marks.image_id = images.id AND 
                 images.image_set_id = image_sets.id AND 
                 marks.user_id = users.id 
                LIMIT $start, 10000";
            $results = $db->runQuery($query);
        }
    }

} /* ----------------------------------------------------------------------
    NOT HERO DATA

    Setup your query, go thru 10 000 marks at a time & write to file
   ---------------------------------------------------------------------- */

else {

    echo "getting data \n";

    $page = 0;

    while ($page < $lastPage) {
        echo ".";

        $start = $page * 10000;
        $query = "SELECT marks.id, 
                 image_sets.name as image_name, 
                 marks.x, marks.y, marks.diameter, marks.type, marks.details, 
                 images.details as origin, 
                 users.name, marks.created_at 
          FROM marks, images, image_sets, users
          WHERE marks.created_at >= '$dateStart' AND 
                marks.created_at < '$dateEnd' AND
                marks.image_id = images.id AND 
                images.image_set_id = image_sets.id AND 
                marks.user_id = users.id
          LIMIT " . $start . ",10000";

        $results = $db->runQuery($query);


        /* ----------------------------------------------------------------------
            read through each row, 1 at a time
           ---------------------------------------------------------------------- */

        foreach ($results as $result) {

            // Fix the X, Y to be in the coordinates of the master image

            // 1) Get the offset from the Master Image
            $origin = json_decode($result['origin'], TRUE);

            // 2) Correct the x, y position info
            $x = $result['x'] + $origin['x'];
            $y = $result['y'] + $origin['y'];

            if ($result['details'] != 'null') {
                $details = json_decode($result['details'], TRUE);   // Details about marks (not always present)

                // only update things if there is an offset
                if ($origin['x'] != 0 || $origin['y'] != 0) {
                    $details['points'][0]['x'] += $origin['x'];
                    $details['points'][0]['y'] += $origin['y'];
                    $details['points'][1]['x'] += $origin['x'];
                    $details['points'][1]['y'] += $origin['y'];
                }

                $details_json = json_encode($details);
            } else {
                $details_json = "null";
            }


            $output .= $result['id'] . "\t" .
                $result['image_name'] . "\t" .
                $x . "\t" .
                $y . "\t" .
                $result['diameter'] . "\t" .
                $result['type'] . "\t" .
                $details_json . "\t" .
                $result['created_at'] . "\t" .
                $result['name'] . "\n";
        }

        fwrite($file, $output);
        $output = "";
        $page++;
    }
}

// Update data_download table to show success
$query = "UPDATE data_downloads SET success = 1 WHERE id = $download_id";
$db->runQuery($query);

// Close things
fclose($file);
$db->closeDB();

// email the user
$mailAlert = new email($emailSettings);
$msg['subject'] = "[CosmoQuestX] Output Ready";
$msg['body'] = "Your download is ready. Please return to " . $BASE_URL . "/science/task=download-data to get your file.";
$mailAlert->sendMail($email_to, $msg);