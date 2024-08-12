<?php

/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

require_once("../../csb-loader.php");
require_once($DB_class);

// Connect to the database

global $db_servername, $db_username, $db_password, $db_name, $db_port;
$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

$application_id = 1;

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle Button Click
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // submit data to table marks
    $imageID = $_POST['imageId'];
    $buttonValue = $_POST['buttonvalue'];
    $userID = $_POST['userId'];

    // Insert Data into image_user table
    $query = "INSERT INTO image_users (image_id, user_id, application_id) VALUES (?, ?, ?)";
    $params = array($imageID, $userID, $application_id);
    $result = $db->runQueryWhere($query, "iii", $params);

    // Insert Data into the Database
    $query = "INSERT INTO image_poll (image_id, user_id, poll) VALUES (?, ?, ?)";
    $params = array($imageID, $userID, $buttonValue);
    $result = $db->runQueryWhere($query, "iis", $params);
}

do {
    // Fetch a Random Image with a NEW image_id
    $query = "SELECT * FROM images WHERE done = 0 ORDER BY RAND() LIMIT 1";
    $result = $db->runQuery($query);
    $imageURL = $result[0]['file_location'];
    $newImageId = $result[0]['id'];
} while (isset($_POST['imageID']) && $newImageId == $_POST['imageID']);

// Send Image Data as JSON
$response = array('imageUrl' => $imageURL, 'imageId' => $newImageId, 'notes' => $notes);
echo json_encode($response);

?>
