<?php
/*
 *  imagechop.php
 *  Takes image source, image size in px, vertical overlap in px, horizontal
 *  overlap in px and output folder. Outputs images according to those
 *  parameters as well as insert the done images into the database afterwards.
 *  This is intended for bulk chopping via upload.
 *  @param int application_id
 *  @param string imagesource
 *  @param int imagesize
 *  @param int voverlap
 *  @param int hoverlap
 *  @return bool success
 */

$debug = false;

// This is a job script. Ensure it is only called from the command line
if (substr(PHP_SAPI, 0, 3) !== 'cli') {
    die ("This script can only be run from the command line");
}

// Check if there are sufficient arguments supplied
if ($argc != 6) {
    die ("Usage: php imagechop.php <application_id> <source_image> <subimagesize> <overlap-h> <overlap-v>");
}
else {
    if ($debug) {
        print "Received " . $argc . "arguments \n";
        var_dump($argv);
        print"\n-------------------------------\n";
    }
}

$csbdir=dirname(getcwd()). DIRECTORY_SEPARATOR ."csb" . DIRECTORY_SEPARATOR;

if ($debug) { print "Working directory is $csbdir, loading configuration \n"; };

// Require the config loader. Assuming the cron folder exists next to csb!
require_once $csbdir.'csb-loader.php';
require_once $DB_class;

$db_conn = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

// Basic sanity checks and variable assignment
$application_id =   filter_var($argv[1],FILTER_SANITIZE_NUMBER_INT);
$imagesource    =   preg_replace("/\/{2}|\.{2}|\\{2}/", '', $argv[2]);
$imagesize      =   filter_var($argv[3],FILTER_SANITIZE_NUMBER_INT);
$hoverlap       =   filter_var($argv[4],FILTER_SANITIZE_NUMBER_INT);
$voverlap       =   filter_var($argv[5],FILTER_SANITIZE_NUMBER_INT);
$image_parts    =   pathinfo($imagesource);
/* There are more supported image formats (see imagetypes()) and in future,
 * WEBP and AVIF might be interesting, but we'll start with the "safe" JPG
 * and PNG and see where it takes us. When extending, please choose the
 * array key to correspond with the value of the IMAGETYPE constant of the
 * format. And if something breaks, this might have changed.
 */
$allowed_formats    =   array(2 => 'jpg',3 => 'png');

// Get image information. We only need the first three, which are all numeric.
// The fourth is the "width" and "height" attributes that can be used in HTML.
$img = getimagesize($imagesource);
if ($img === false) {
    die ("Error opening master image file\n");
}

$baseimagewidth     =   $img[0];
$baseimageheight    =   $img[1];
$baseimagetype      =   $img[2];
$baseimageattr      =   $img[3];


$curwidth       =   0;
$curheight      =   0;

// Get the application name
$app_sql = "SELECT name from applications where id = ?";
$app_params = array($application_id);
$app_res = $db_conn->runQueryWhere($app_sql,"i",$app_params);
if ($app_res === false) {
    // If we couldn't find the application name, bug out with a server error
    print "Could not find application name: " . mysqli_errno() . ": " . mysqli_error();
    $db_conn->closeDB();
    exit();
}
$application_name = $app_res[0]['name'];
if ($debug) { print "Chopping images for application $application_name \n"; };

// Initialize variables for the raw images
$imagepart      =  'csb-apps' . DIRECTORY_SEPARATOR . $application_name;
$imagebasedir   =   $BASE_DIR . $imagepart;
$imagebaseurl   =   $BASE_URL . $imagepart;
$imagefolder    =   $imagebasedir . DIRECTORY_SEPARATOR . "raw_images";

// Initialize variables for the subimages
$subimage       =   null;
$subimagenumber =   1;
$subimagefolder =   $imagebasedir . DIRECTORY_SEPARATOR ."sub_images";
$subimagebase   =   $image_parts['filename'];
$subimageext    =   strtolower($image_parts['extension']);

// Check if the file we're opening is a format we can deal with. If not, bug out.
if (!array_key_exists($baseimagetype, $allowed_formats)) {
    die ("Not supported file type");
}
// Choose the function names for the format of the raw image.
$imageload  = "imagecreatefrom" . $allowed_formats[$baseimagetype];
$imagesave  = "image" . $allowed_formats[$baseimagetype];

// Make sure our destination folders exist
if (is_dir($imagefolder) === false) {
    mkdir($imagefolder, 0777, true);
}
else {
    if (is_writable($imagefolder) === false) {
        die ("Raw image folder exists but is not writable!\n");
    }
}
if (is_dir($subimagefolder) === false) {
    mkdir($subimagefolder, 0777, true);
}
else {
    if (is_writable($subimagefolder) === false) {
        die ("Subimage folder exists but is not writable!\n");
    }
}

// First, copy our raw image
copy($imagesource, $imagefolder . DIRECTORY_SEPARATOR . basename($imagesource));

// Database, round 2
$db_conn = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

// Before we chop, write the imageset
$imageset_sql    =  "INSERT INTO image_sets (name, application_id, created_at, updated_at)" .
    "VALUES (?, ?,now(),now())";
$imageset_params =  array($subimagebase,$application_id);

if($debug) {
    print "Executing SQL: INSERT INTO image_sets (name, application_id, created_at, updated_at) " .
        "VALUES ($subimagebase,$application_id, now(), now()) \n";
}
$imageset_res    =  $db_conn->insert($imageset_sql,"si",$imageset_params);
if ($imageset_res === false) {
    // If we couldn't insert, bug out with a server error
    print "Could not insert imageset: " . mysqli_errno() . ": " . mysqli_error();
    $db_conn->closeDB();
    exit(1);
}
else {
    // Get the imageset id
    $imagesetid_sql =   'SELECT id from image_sets where name = "' . $subimagebase .'"';
    $imagesetid_res =   $db_conn->runBaseQuery($imagesetid_sql);
    if ($debug) {
        print "\n--------------------\n";
        print "Dumping result of imageset_id query: \n";
        var_dump($imagesetid_res);
    }
    
    if ($imagesetid_res===false) {
        print "Could not get imageset id!";
        $db_conn->closeDB();
        exit(1);
    }
}
$imagesetid     =   $imagesetid_res[0]['id'];

// Walk over the image width, stepping forward the image width minus the overlap, from left to right and top to bottom.
for ($curheight=0; $curheight <= $baseimageheight-$imagesize; $curheight = $curheight + ($imagesize - $voverlap)) {
    for ($curwidth=0; $curwidth <= $baseimagewidth-$imagesize; $curwidth = $curwidth + ($imagesize - $hoverlap)) {
        if($debug) { print "Chopping image $subimagenumber with x=$curwidth, y=$curheight, imagesize=$imagesize and overlap h=$hoverlap, v=$voverlap \n"; }
        // Create a new image object from the base image
        $baseimage    = $imageload($imagesource);
        // assemble the imagename
        $subimagename = $subimagebase. "_" . $subimagenumber .  "." . $subimageext;
        // crop the image to the right format
        $subimage     = imagecrop($baseimage,array(
            'x' => $curwidth,
            'y' => $curheight,
            'width' => $imagesize,
            'height' => $imagesize
        ));
        $subimagefqn  = $subimagefolder . DIRECTORY_SEPARATOR . $subimagename;
        print "Calling $imagesave to save $subimagefqn \n";
        // Write the image to the filesystem
        $imagesave($subimage, $subimagefqn);
        // store the filename in an array
        
        $subimageurl  = $imagebaseurl . DIRECTORY_SEPARATOR . "CHOPPEDIMAGES" . DIRECTORY_SEPARATOR . $subimagename;
        $subimagedetail='{"x":' . $curheight .', "y":'.$curwidth.'}';
        //For each subimage in the imageset, write an entry into the images table
        $image_sql    = 'INSERT INTO images (image_set_id, application_id, name, file_location, details) VALUES (?,?,?,?,?)';
        $image_params = array(
            $imagesetid,
            $application_id,
            $subimagename,
            $subimageurl,
            $subimagedetail
        );
        $image_res    = $db_conn->insert($image_sql, "iisss", $image_params);
        // Bug out if that fails
        if ($image_res===false) {
            print "Could not write image to db!";
            $db_conn->closeDB();
            exit();
        }
        
        //Finally, increment the counter
        $subimagenumber++;
    }
}

?>