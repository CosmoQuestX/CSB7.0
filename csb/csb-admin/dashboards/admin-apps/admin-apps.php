<?php

function doSwitch($db, $option) {
    switch ($option) {
        case "create":
            $text =  create();
            break;
        case "show":
            $text =  showApp();
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
    $main = "<span class='font-weight-bold'>Current Applications: </span><br>";
    $notes = "Instructions will go here";

    // Connect to Databass
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Get names of all applications in the database
    $query = "SELECT name, active FROM applications";
    $result = $db->runQuery($query);

    // if there are no results, add the phrase "none" to $main
    if (!$result) {
        $main .= "ERROR No Apps are available<br>";
    }
    else
    {
        $main .="<ul>";
        // list the names in the result
        foreach ($result as $row) {
            $url = $BASE_URL . "/csb-admin/index.php?option=admin-apps&name=" . $row['name'];
            if ($row['active'] == 1) {
                $status = "Active";
            }
            else {
                $status = "Inactive";
            }
            $main .= "<li><a href='$url'>" . $row['name'] . "</a> ($status)</li>";
        }
        $main .="</ul>";
    }

    // if Name value is in the URL, call showApp function
    if (isset($_GET['name'])) {
        $text = showApp();
        $main .= $text['main'];
    }

    // add form to create new application with all data in the database
    $main .= "<span class='font-weight-bold'>Create or Update Applications: </span><br>";
    $main .= "
        <form id='profile-form' action='$BASE_URL/csb-admin/index.php?option=admin-apps' method='POST'>
            <input type='hidden' name='action' value='create'>
            <label for='name' class='mt-4'>Name:</label>
            <input type='text' name='name' id='name' class='mt-4'><br/>
            <label for='title' class='mt-4'>Title:</label>
            <input type='text' name='title' id='title' class='mt-4'><br/>
            <label for='background_url' class='mt-4'>URL:</label>
            <input type='text' name='background_url' id='background_url' class='mt-4'><br/>
            <label for='project-id' class='mt-4'>Project ID:</label>
            <input type='text' name='project-id' id='project-id' class='mt-4'><br/>
            <label for='status' class='mt-4'>Status:</label>
            <input type='text' name='status' id='status' class='mt-4'><br/>
            <input type='submit' value='Create Application' class='btn btn-cq mt-4 right'>
        </form>
        ";
    return array("main" => $main, "notes" => $notes);

}

function create()
{
    global $db_servername, $db_username, $db_password, $db_name, $db_port, $BASE_URL;

    // Connect to Databass
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Fetch old data to compare.
    $query = "SELECT * FROM applications";
    $result = $db->runQuery($query);

    $changed = FALSE;

  // Take information from form and put it in variables
    $name = $_POST['name'];
    $title = $_POST['title'];
    $url = $_POST['background_url'];
    $project_id = $_POST['project-id'];
    $active = $_POST['status'];

    // Check if the name is already in the database
    foreach ($result as $row) {
        if ($row['name'] == $name) {
            $changed = TRUE;
        }
    }

    // If the name is not in the database, add it
    if (!$changed) {
        $query = "INSERT INTO applications (name, title, background_url, project_id, active) VALUES (?, ?, ?, ?, ?)";
        $params = array($name, $title, $url, $project_id, $active);
        $result = $db->runQueryWhere($query, "sssii", $params);
    }
    else {
        $query = "UPDATE applications SET title = ?, background_url = ?, project_id = ?, active = ? WHERE name = ?";
        $params = array($title, $url, $project_id, $active, $name);
        $result = $db->runQueryWhere($query, "ssiis", $params);
    }

    // Return the main text
    $main = "Application " . $_POST['name'] . " has been created or updated";
    return array("main" => $main, "notes" => "");
}

function showApp()
{
    global $db_servername, $db_username, $db_password, $db_name, $db_port, $BASE_URL;

    // Connect to Database
    $db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

    // Get the name of the application from the URL
    $name = $_GET['name'];

    $query = "SELECT * FROM applications WHERE name = ?";
    $params = array($name);
    $result = $db->runQueryWhere($query, "s", $params);

    if (!$result) {
        $main = "ERROR: No application found with name $name";
    }
    else {
        $main = "<span class='font-weight-bold'>Details for Application <span class='font-italic'>" . $result[0]['name'] . ":</span></span></br>";
        $main .= "<p>Title: " . $result[0]['title'] . "</br>";
        $main .= "Project ID: " . $result[0]['project_id'] . "</br>";
        $main .= "Status: " . $result[0]['active'] . "</br>";
        $main .= "Description: " . $result[0]['description'] . "</br>";
        $main .= "Background URL: " . $result[0]['background_url'] . "</br>";
        $main .= "<img style='width: 300px;' src='" . $result[0]['background_url'] . "' alt='background image'></p>";
    }
    return array("main" => $main, "notes" => "");
}
