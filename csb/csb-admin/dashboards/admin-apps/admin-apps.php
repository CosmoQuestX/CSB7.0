<?php

function doSwitch($db, $option) {
    switch ($option) {
        case "create":
            $text =  create($db);
            break;
        default:
            $text['main'] = "<p>ERROR: No Option Selected. Did you try URL hacking?</p>";
            $text['notes'] = "";
    }
    return array("main" => $text['main'], "notes" => $text['notes']);
}

function landing()
{
    global $db_servername, $db_username, $db_password, $db_name, $db_port, $BASE_URL;
    $main = "Turn apps on and off";
    $notes = "Instructions will go here";

    // Connect to Databass
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Get names of all applications in the database
    $query = "SELECT name FROM applications";
    $result = $db->runQuery($query);

    // if there are no results, add the phrase "none" to $main
    if (!$result) {
        $main .= "No Apps are available<br>";
    }
    else
    {
        // list the names in the result
        foreach ($result as $row) {
            $main .= $row['name'] . " - ";
        }
    }

    // add form to create new application with all data in the database
    $main .= "
        <form id='profile-form' action='$BASE_URL/csb-admin/index.php?option=admin-apps' method='POST'>
            <input type='hidden' name='action' value='create'>
            <label for='name'>Name:</label>
            <input type='text' name='name' id='name' class='mt-4'>
            <label for='description'>Description:</label>
            <input type='text' name='description' id='description' class='mt-4'>
            <label for='url'>URL:</label>
            <input type='text' name='url' id='url' class='mt-4'>
            <label for='status'>Status:</label>
            <input type='text' name='status' id='status' class='mt-4'>
            <input type='submit' value='Create Application' class='btn btn-cq mt-4 right'>
        </form>
        ";
    return array("main" => $main, "notes" => $notes);

}

function create($db)
{
    global $db_servername, $db_username, $db_password, $db_name, $db_port, $BASE_URL;

    // Connect to Databass
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Fetch old data to compare.
    $query = "SELECT * FROM applications";
    $result = $db->runQuery($query);

    $changed = FALSE;

  // Get the data from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $status = $_POST['status'];

    // Check if the name is already in the database
    foreach ($result as $row) {
        if ($row['name'] == $name) {
            $changed = TRUE;
        }
    }

    // If the name is not in the database, add it
    if (!$changed) {
        $query = "INSERT INTO applications (name, description, url, status) VALUES (?, ?, ?, ?)";
        $params = array($name, $description, $url, $status);
        //$result = $db->runQueryWhere($query, "ssss", $params);
        $status = "created";
    }

    // If the name is in the database, update it
    else {
        $query = "UPDATE applications SET description = ?, url = ?, status = ? WHERE name = ?";
        $params = array($description, $url, $status, $name);
        //$result = $db->runQueryWhere($query, "ssss", $params);
        $status = "updated";
    }

    // Return the main text
    $main = "Application " . $_POST['name'] . " $status";
    return array("main" => $main, "notes" => "");
}
