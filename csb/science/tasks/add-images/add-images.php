<?php

$main = "<h1>Add Images to Database</h1>";

// If they are uploading a file, save that first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main .= addImages();
}

// And before you end the page, let them do an(other) image
$main .= uploadForm();

/* ----------------------------------------------------------------------
    Functions are below
    ---------------------------------------------------------------------- */

function uploadForm() {
    global $db_servername, $db_username, $db_password, $db_name, $db_port;

    // Connect to Databass
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Fetch old data to compare.
    $query = "SELECT * FROM applications";
    $result = $db->runQuery($query);

    // Create a form to upload a text file and get all the variables needed to add the images to the database
    $form = "<span class='font-weight-bold'>Add image info to the Database </span><br>";
    $form .= "<form action='index.php?task=add-images' method='post' enctype='multipart/form-data'>";
    $form .= "<input type='file' name='fileToUpload' id='fileToUpload'><br/>";
    // Create a dropdown of possible applications the images could belong too
    $form .= "<label for='application' class='mt-4'>Application: </label><br/>";
    $form .= "<select name='application' id='application'>";
    foreach ($result as $row) {
        $name = $row['name'];
        $appId = $row['id'];
        $form .= "<option value='$appId'>$name</option>";
    }
    $form .= "</select><br/>";

    $form .= "<label for='fileLocation' class='mt-4'>File Location: </label><br/>";
    $form .= "<input type='text' name='fileLocation' id='fileLocation'><br/>";
    // Add lines for priority, sun_angle, maxLat, minLat, maxLon, minLon, pixSize, description, details
    $form .= "<label for='priority' class='mt-4'>Priority: </label><br/>";
    $form .= "<input type='number' name='priority' id='priority' value='1'><br/>";
    $form .= "<label for='sun_angle' class='mt-4'>Sun Angle: </label><br/>";
    $form .= "<input type='number' name='sun_angle' id='sun_angle'><br/>";
    $form .= "<label for='maxLat' class='mt-4'>Max Latitude: </label><br/>";
    $form .= "<input type='number' name='maxLat' id='maxLat'><br/>";
    $form .= "<label for='minLat' class='mt-4'>Min Latitude: </label><br/>";
    $form .= "<input type='number' name='minLat' id='minLat'><br/>";
    $form .= "<label for='maxLon' class='mt-4'>Max Longitude: </label><br/>";
    $form .= "<input type='number' name='maxLon' id='maxLon'><br/>";
    $form .= "<label for='minLon' class='mt-4'>Min Longitude: </label><br/>";
    $form .= "<input type='number' name='minLon' id='minLon'><br/>";
    $form .= "<label for='pixSize' class='mt-4'>Pixel Size: </label><br/>";
    $form .= "<input type='number' name='pixSize' id='pixSize'><br/>";
    $form .= "<label for='description' class='mt-4'>Description: </label><br/>";
    $form .= "<input type='text' name='description' id='description'><br/>";
    $form .= "<label for='details' class='mt-4'>Details: </label><br/>";
    $form .= "<input type='text' name='details' id='details'><br/><br/>";

    $form .= "<input type='submit' value='Upload Image' name='submit'>";

    return $form;

}

function addImages()
{
    global $db_servername, $db_username, $db_password, $db_name, $db_port, $db;
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    $main = "<p><span class='font-weight-bold'>Adding Image to Database</span><br>";
    $main .= "Reading in images from: " . $_FILES["fileToUpload"]["name"] . "<br>";

    // Get the uploaded file's name and open it to read
    if ($_FILES["fileToUpload"]['error'] > 0) {
        die("Error: " . $_FILES["fileToUpload"]["error"]);
    }
    $target_file = $_FILES["fileToUpload"]["tmp_name"];
    $file = fopen($target_file, "r") or die("Unable to open file!");

    //setup database query
    $query = "INSERT INTO image_sets (";
    $format = "";
    $params = array();
    $end = "";

    // get the first line of the file and echo it to the screen
    $line = fgets($file);
    $query .= "name";
    $format .= "s";
    $end .= "?";
    array_push($params, $line);

    // Get the rest of the values from the form and put them in variables. If they are empty, set them to NULL
    if (isset($_POST['application']) && !empty($_POST['application'])) {
        $application = $_POST['application'];
        $query .= ", application_id";
        array_push($params, $application);
        $format .= "i";
        $end .= ", ?";
    } else {
        die("No application selected");
    }

    if (isset($_POST['priority']) && !empty($_POST['priority'])) {
        $priority = $_POST['priority'];
        $query .= ", priority";
        array_push($params, $priority);
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['sun_angle']) && !empty($_POST['sun_angle'])) {
        $sun_angle = $_POST['sun_angle'];
        $query .= ", sun_angle";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['maxLat']) && !empty($_POST['maxLat'])) {
        $maxLat = $_POST['maxLat'];
        $query .= ", maximum_latitude";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['minLat']) && !empty($_POST['minLat'])) {
        $minLat = $_POST['minLat'];
        $query .= ", minimum_latitude";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['maxLon']) && !empty($_POST['maxLon'])) {
        $maxLon = $_POST['maxLon'];
        $query .= ", maximum_longitude";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['minLon']) && !empty($_POST['minLon'])) {
        $minLon = $_POST['minLon'];
        $query .= ", minimum_longitude";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['pixSize']) && !empty($_POST['pixSize'])) {
        $pixSize = $_POST['pixSize'];
        $query .= ", pixel_resolution";
        $format .= "d";
        $end .= ", ?";
    }

    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $description = $_POST['description'];
        $query .= ", description";
        $format .= "s";
        $end .= ", ?";
    }

    if (isset($_POST['details']) && !empty($_POST['details'])) {
        $details = $_POST['details'];
        $query .= ", details";
        $format .= "s";
        $end .= ", ?";
    }
    $query .= ") VALUES (" . $end . ")";

    $result = $db->runQueryWhere($query, $format, $params);
    $main .= "Master Image added to database</br>";

    //Get the master images id
    $query = "SELECT id FROM image_sets WHERE name = '$line'";
    $result = $db->runQuery($query);
    $imageSetId = $result[0]['id'];

    // Get the file location from the form
    $fileLocation = $_POST['fileLocation'];

    // Loop through the rest of the file and add the images to the database
    while (!feof($file)) {
        $line = fgets($file);
        if (!empty($line)) {
            $main .= "Adding image: $temp</br>";
            if (isset($_POST['sun_angle']) && !empty($_POST['sun_angle'])) {
                $query = "INSERT INTO images (image_set_id, application_id, name, file_location, sun_angle) VALUES (?, ?, ?, ?, ?)";
                $params = array($imageSetId, $application, $line, $temp, $sun_angle);
                $result = $db->runQueryWhere($query, "iissd", $params);
            } else {
                $query = "INSERT INTO images (image_set_id, application_id, name, file_location) VALUES (?, ?, ?, ?)";
                $params = array($imageSetId, $application, $line, $temp);
                $result = $db->runQueryWhere($query, "iiss", $params);
            }
        }
    }
    $main .= "Sub-Images added to database: $temp</br>";

    return $main;
}
