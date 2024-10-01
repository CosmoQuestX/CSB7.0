<?php

// 1) Get URL and meta data for a Master Image
// 2) Save image in temp directory & write meta data to DB
// 3) Display master image and overlay with squares marking proposed thumbnails with default overlap
//      a) sub images are 450x450 pixels
//      b) default overlap is 45 pixels
//      c) alternate default is to calculate how to space squares so they are distributed in height & width
// 4) Allow user to edit proposed chops by entering how many pixels overlap they want
// 5) Chop the image and add sub-images to DB

$main = "<h1>Image Chop</h1>";

// If they are uploading a file, save that first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main .= uploadSaveFile();
}

// And before you end the page, let them do an(other) image
$main .= uploadForm();

/* ----------------------------------------------------------------------
    Functions are below
    ---------------------------------------------------------------------- */
function uploadForm() {
    $form = "<span class='font-weight-bold'>Upload an Image: </span><br>";
    $form .= "<form action='index.php?task=image-chop' method='post' enctype='multipart/form-data'>";
    $form .= "<input type='file' name='fileToUpload' id='fileToUpload'><br/>";
    $form .= "<label for='stampSize' class='mt-4'>Stamp Size: </label><br/>";
    $form .= "<input type='number' name='stampSize' id='stampSize' value='450'> pixels</br>";
    $form .= "<label for='overlap' class='mt-4'>Overlap: </label><br/>";
    $form .= "<input type='number' name='overlap' id='overlap' value='45'> pixels</br>";
    $form .= "<input type='submit' value='Upload Image' name='submit'>";

    $form .= "</form>";
    return $form;
}

function uploadSaveFile()
{
    // get the file name
    global $BASE_DIR;

    $target_file = $BASE_DIR . "uploads/" . basename($_FILES["fileToUpload"]["name"]);
    $main = "<p><span class='font-weight-bold'>Chopping: $target_file</span><br>";

    // Set up the upload
    $target_dir = $BASE_DIR . "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $main .= "File is an image - " . $check["mime"] . ".<br>";
            $uploadOk = 1;
        } else {
            $main .= "File is not an image.<br>";
            $uploadOk = 0;
        }
    }

    // put the other form variables into the main
    $stampSize = $_POST['stampSize'];
    $overlap = $_POST['overlap'];

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $main .= "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $main .= "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            $main .= "Sorry, there was an error uploading your file.";
        }
    }
    $main .= "</p>";

    // Chop the image
    $main .= chopFile($stampSize, $overlap);

    $main .= "To use these images in an application, you will need to use the text file to add things to the database. ";
    $main .= "The text file contains the names of the images that were created. You may want to move the files from the uploads ";
    $main .= "directory to another location like S3. ";
    return $main;
}

function chopFile($stampSize, $overlap)
{
    global $BASE_URL, $BASE_DIR;
    $extlen = strlen(pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION))+1;
    $rootFilename = substr(basename($_FILES["fileToUpload"]["name"]), 0, -$extlen);

    // Create a text file with the name of the image.txt
    $file = fopen($BASE_DIR . "uploads/" . $rootFilename . ".txt", "w") or die("Unable to open file!");

    // write file name to the file
    fwrite($file, basename($_FILES["fileToUpload"]["name"]) . "\n");

    // Get the image height and width
    list($width, $height) = getimagesize($BASE_DIR . "uploads/" . basename($_FILES["fileToUpload"]["name"]));

    // cycle through the image in width
    for ($x = 0; $x < $width - $overlap; $x += $stampSize - $overlap) {
        // cycle through the image in height
        for ($y = 0; $y < $height - $overlap; $y += $stampSize - $overlap) {

            // sort image width if it doesn't fit the full width
            if ($x + $stampSize > $width) {
                $delX = $width - $x;
            } else {
                $delX = $stampSize;
            }
            // sort image height if it doesn't fit the full height
            if ($y + $stampSize > $height) {
                $delY = $height - $y;
            } else {
                $delY = $stampSize;
            }

            // copy pixels x,y to x+450,y+450 into a new image
            $im = imagecreatefrompng($BASE_DIR . "uploads/" . basename($_FILES["fileToUpload"]["name"]));
            $im1 = imagecreatetruecolor($stampSize, $stampSize);
            imagecopy($im1, $im, 0, 0, $x, $y, $delX, $delY);
            imagepng($im1, $BASE_DIR . "uploads/" . $rootFilename . "_$x-$y.png");
            imagedestroy($im1);
            imagedestroy($im);

            // write each sub-image to the file
            fwrite($file, $rootFilename . "_$x-$y.png\n");
        }
    }
    fclose($file);
    // return a link to the text file
    $main .= "<a href='$BASE_URL/uploads/$rootFilename.txt'>Download Chopped Images</a><br/>>";

    return $main;
}
