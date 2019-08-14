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

/* ----------------------------------------------------------------------
   Open the file
   ---------------------------------------------------------------------- */

    $output = "";

    if (isset($_GET)) {

        $page	= $_GET['page'];

        // Setup the Filename
        if ($page == 0) {
            $filepath = $BASE_DIR."temp/data_".date("Y-m-d.His").".csv";
        } else {
            $filepath = $_GET['file'];
        }

        // Does it have the dates set up? Only check on page 0
        if (isset($_GET['data'])) {
            $dateStart = $_GET['data'];
            $dateEnd = date("Y-m-d", strtotime("$dateStart +7 day"));
        } else {
            die("You need to specify a starting date - DON'T URL HACK");
        }


        // echo "checking $dateStart to $dateEnd";

        // Open the file
        if ($file = fopen($filepath, a)) {
            if ($page == 0) $output .= "mark_id\timage_name\tx\ty\tdiameter\ttype\tdetails\tdate\tuser\n";
        }
        // throw an error if just won't open
        else {
            $db->closeDB();
            die("couldn't open temporary file on server");
        }

        // If it's the first page, get the number of lines

        if ($page == 0) {
            $where = "WHERE created_at >= '$dateStart'
                      AND created_at < '$dateEnd'";
            $numRows = $db->getNumRows('marks', $where);
            $lastPage = intval($numRows/10000.0);

            if ( ($numRows % 10000) != 0) $lastPage++; // round up to get the last page
        }
        // otherwise get the lastPage from the GET
        else {
            if (isset($_GET['lastPage'])) {
                $lastPage = $_GET['lastPage'];
            } else {
                $db->closeDB();
                die("mal-formed request: lastPage missing. Don't HACK URLs.");
            }

       }

        // If it's not last page, output please wait
        if ($page != $lastPage) {
            ?>
            <html>
            <body style="background = #FFFFFF url('<?php echo $BASE_URL;?>csb-content/images/buttons/Loading.gif') center center no-repeat;">
            <H2>PLEASE WAIT</H2>
            <p>
                Currently outputting <?php echo $page; ?> / <?php echo $lastPage; ?>
            </p>
            </body>
            </html>
            <?php
        }

    } else {
        $db->closeDB();
        die("Incorrectly submitted request. Don't Hack URLs People!");
    }

/* ----------------------------------------------------------------------
    Setup your query, go thru 10 000 marks at a time & write to file
   ---------------------------------------------------------------------- */

    $start = $page * 10000;
    //$start = 1000;

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
              LIMIT ".$start.",10000";

    //echo $query;

    $results = $db->runQuery($query);
    print_r($results);

/* ----------------------------------------------------------------------
    read through each row, 1 at a time
   ---------------------------------------------------------------------- */

    foreach ($results as $result) {

        // Fix the X, Y to be in the coordinates of the master image

        // 1) Get the offset from the Master Image
        $origin  = json_decode($result['origin'], TRUE);

        // 2) Correct the x, y position info
        $x = $result['x'] + $origin['x'];
        $y = $result['y'] + $origin['y'];

        if (!isset($details) && $result['details'] != 'null') {
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
            $details = "null";
        }


        $output .=  $result['id'] . "\t" .
                    $result['image_name'] . "\t" .
                    $x . "\t" .
                    $y . "\t" .
                    $result['diameter'] . "\t" .
                    $result['type'] . "\t" .
                    $details_json . "\t" .
                    $result['created_at'] . "\t" .
                    $result['name'] . "\n";
    }


// 3) When you get to the last page, output temp file to web

// after the if statements in the paging, write to file
fwrite($file, $output);
fclose($file);


if ($page < $lastPage) {

    $page++;
    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?app_id=21&combined=FALSE&page=".$page."&lastPage=".$lastPage."&data=".$dateEnd."&file=".$filepath;

    ?>
    <html>
    <head>
        <meta http-equiv="refresh" content="0;URL=<?php echo $url;?>" />
    </head>
    </html>
    <?php

} else {
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        exit;
    }

    $filePtr = fopen($filepath, a);
    fclose($filePtr);
    unlink($filepath);
}
