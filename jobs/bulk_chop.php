<?php
/*
 * bulk_chop.php
 * This is intended for chopping large amounts of identical (!) images. Takes
 * a zip file with any number of images and a single text file named chopconfig
 * with the following information:
 * application_id          The id of the application you are assigning the
 *                         images to. You can get this from the project
 *                         dashboard.
  * TODO: Make sure the application id is displayed on the project dashboard ;)
 * imagesize               The size for the chopped images
 * hoverlap                Horizontal overlap in pixels
 * voverlap                Vertical overlap in pixels
 * The important thing is that the overlap values need to be chosen to result
 * in usable output images. If you do bulkchop, the rule is:
 * "Garbage in, garbage out!"
 */


$debug = false;

/* There are more supported image formats (see imagetypes()) and in future,
 * WEBP and AVIF might be interesting, but we'll start with the "safe" JPG
 * and PNG and see where it takes us. When extending, please choose the
 * array key to correspond with the value of the IMAGETYPE constant of the
 * format. And if something breaks, this might have changed.
 */
$allowed_formats    =   array(2 => 'jpg',3 => 'png');

// This is a cron job. Ensure it is only called from the command line
if (substr(php_sapi_name(), 0, 3) !== 'cli') {
    die ("This script can only be run from the command line");
}

// Check if there are sufficient arguments supplied
if ($argc < 2) {
    die ("Usage: php bulk_chop.php <source_zipfile>");
}
else {
    if ($debug) {
        print "Received " . $argc . " arguments \n";
        var_dump($argv);
    }
}

$csbdir=dirname(getcwd()). DIRECTORY_SEPARATOR ."csb" . DIRECTORY_SEPARATOR;

if ($debug) { print "Working directory is $csbdir, loading configuration \n"; };

// Require the config loader. Assuming the jobs folder exists next to csb!
require_once $csbdir.'csb-loader.php';

// Basic sanitizing and variable assignment
$zipfile    =   preg_replace("/\/{2}|\.{2}|\\{2}/", '', $argv[1]);


// Make sure we are getting passed a filename that points to a file
if (file_exists($zipfile) === false) {
    die ("File $zipfile does not exist");
}


$zip    =   new ZipArchive;
// Try to open the file, will fail if not a zipfile
$res    =   $zip->open($zipfile);
if ($res === false) {
    die ("Are you sure this is a zipfile?");
}
else {
    //Now that we opened the sesame, let's extract the contents into a temporary directory
    $fh_tempdir     =   tempdir();
    // Make sure the directory ends in a directory separator
    If (substr($fh_tempdir,-1) != DIRECTORY_SEPARATOR) {
        $fh_tempdir .= DIRECTORY_SEPARATOR;
    }
    
    $res            =   $zip->extractTo($fh_tempdir);
    if ($res === false) {
        die ("Could not extract zip file into temporary directory, giving up");
    }
    // We don't need to keep the source open, clean up before continuing
    $zip->close();
    // Maybe we should clean up?
    //unlink($zipfile);
    
    // See if there is a configuration present
    if (file_exists($fh_tempdir . "chopconfig") === false) {
        // Clean up before exiting
        cleanup();
        die ("Could not read config, please check zipfile");
    }
    // We're expecting an ini-style chop config
    $chopconfig     =   parse_ini_file($fh_tempdir . "chopconfig");
    /* 
     * The chop config should consist of four parameters:
     * - application id
     * - image size
     * - horizontal overlap
     * - vertical overlap
     * If it doesn't, we shouldn't chop 
     */
    if (count($chopconfig) <> 4) {
        if ($debug) {
            print "Looks like the chopconfig is invalid";
            var_dump($chopconfig);
        }
        // Clean up before exiting
        cleanup();
        die("Invalid chop configuration file");
    }
    else {
        // check presence 
        if (isset($chopconfig['application_id'])) {
            //sanitize input
            $application_id =   filter_var($chopconfig['application_id'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (isset($chopconfig['image_size'])) {
            //sanitize input
            $image_size     =   filter_var($chopconfig['image_size'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (isset($chopconfig['hoverlap'])) {
            //sanitize input
            $hoverlap       =   filter_var($chopconfig['hoverlap'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (isset($chopconfig['voverlap'])) {
            //sanitize input
            $voverlap       =   filter_var($chopconfig['voverlap'], FILTER_SANITIZE_NUMBER_INT);
        }
        // Now, if all is set
        if (isset($application_id) && isset($image_size) && isset($hoverlap) && isset($voverlap)) {
            if ($debug) {
                print "Looks like we are good to go :)\n";
            }
        }
        else {
            if ($debug) {
                print "Looks like the chopconfig is invalid\n";
                var_dump($chopconfig);
            }
            // Clean up before exiting
            cleanup();
            die ("Invalid configuration!");
        }
    }
    
    // Let's check the contents of the zip file whether the image size is equal
    // before we write nonsense into the database
    foreach (glob($fh_tempdir . "*.*") as $masterimage) {
        // Now, test the images
        list ($imagewidth, $imageheight, $imagetype, $imageattr)  = getimagesize($masterimage);
        // Check if the file we're opening is a format we can deal with. If not, bug out.
        if (!array_key_exists($imagetype, $allowed_formats)) {
            // Clean up before exiting
            cleanup();
            die ("Unsupported file type $imagetype in zip archive");
        }
        if (isset($zipimagewidth) && isset($zipimageheight)) {
            
            if (!($imagewidth == $zipimagewidth) || !($imageheight == $zipimageheight)) {
                // Clean up before exiting
                cleanup();
                die("Images in the zip file are not the same size; exiting");
            }
        }
        else {
            // This should only happen once
            if (is_int($imagewidth) && is_int($imageheight))
            $zipimagewidth  =   $imagewidth;
            $zipimageheight =   $imageheight;
        }
    }
    // If we came here, the images in the zip file should all be the same
    // dimensions, and we also have a config. Let's chop!
    if($debug) {
        "Setup for chopping, images ready, calling imagechop.";
    }
    
    foreach (glob($fh_tempdir . "*.*") as $masterimage) {
        // Now, before we actually chop something, test it
        if($debug) {
            print "Executing php " . __DIR__ . DIRECTORY_SEPARATOR . "imagechop.php" . " $application_id $masterimage $image_size $hoverlap $voverlap \n";
        }
        exec( "php " . __DIR__ . DIRECTORY_SEPARATOR . "imagechop.php" . " $application_id $masterimage $image_size $hoverlap $voverlap");
    }
}

// Finally, time to clean up
cleanup();



/**
 * function tempdir
 * source: https://stackoverflow.com/questions/1707801/making-a-temporary-dir-for-unpacking-a-zipfile-into
 * Creates a random unique temporary directory, with specified parameters,
 * that does not already exist (like tempnam(), but for dirs).
 *
 * Created dir will begin with the specified prefix, followed by random
 * numbers.
 *
 * @link https://php.net/manual/en/function.tempname.php
 *
 * @param string|null $dir Base directory under which to create temp dir.
 *     If null, the default system temp dir (sys_get_temp_dir()) will be
 *     used.
 * @param string $prefix String with which to prefix created dirs.
 * @param int $mode Octal file permission mask for the newly-created dir.
 *     Should begin with a 0.
 * @param int $maxAttempts Maximum attempts before giving up (to prevent
 *     endless loops).
 * @return string|bool Full path to newly-created dir, or false on failure.
 */
function tempdir($dir = null, $prefix = 'tmp_', $mode = 0700, $maxAttempts = 1000)
{
    /* Use the system temp dir by default. */
    if (is_null($dir))
    {
        $dir = sys_get_temp_dir();
    }
    
    /* Trim trailing slashes from $dir. */
    $dir = rtrim($dir, DIRECTORY_SEPARATOR);
    
    /* If we don't have permission to create a directory, fail, otherwise we will
     * be stuck in an endless loop.
     */
    if (!is_dir($dir) || !is_writable($dir))
    {
        return false;
    }
    
    /* Make sure characters in prefix are safe. */
    if (strpbrk($prefix, '\\/:*?"<>|') !== false)
    {
        return false;
    }
    
    /* Attempt to create a random directory until it works. Abort if we reach
     * $maxAttempts. Something screwy could be happening with the filesystem
     * and our loop could otherwise become endless.
     */
    $attempts = 0;
    do
    {
        $path = sprintf('%s%s%s%s', $dir, DIRECTORY_SEPARATOR, $prefix, mt_rand(100000, mt_getrandmax()));
    } while (
        !mkdir($path, $mode) &&
        $attempts++ < $maxAttempts
        );
    
    return $path;
}


/*
 * function deletedir
 * @param string dirname The directory to delete
 * @return bool success (or not)
 */
function deletedir($dirname) 
{
    if(is_dir($dirname)) {
        // For all entries in the directory
        foreach (glob($dirname . "*", GLOB_MARK) as $file) {
            if (is_dir($file)) {
                // Not expecting directories, but who knows what people will upload
                deletedir($file);
            }
            else {
                // delete the file
                unlink($file);
            }
        }
        // Clean up the rest after we're done
        rmdir($dirname);
        return true;
    }
    else {
        //Don't work on files
        return false;
    }
}

function cleanup() 
{
    global $fh_tempdir;
    // Clean up before exiting
    $rt         =   deletedir($fh_tempdir);
    if ($rt === false) {
        print "Error deleting $fh_tempdir, please remove manually!\n";
    }
}
?>