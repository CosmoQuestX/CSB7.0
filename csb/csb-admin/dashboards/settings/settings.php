<?php
function doSwitch($db, $option) {
    switch ($option) {
        case "update":
            $text =  update($db);
            break;
        default:
            $text['main'] = "<p>ERROR: No Option Selected. Did you try URL hacking?</p>";
            $text['notes'] = "";
    }
    return array("main" => $text['main'], "notes" => $text['notes']);
}

function landing ($db, $msg="") {

    global $BASE_URL;
    $main = "";
    $notes = "";

    // Request options table
    $query = "SELECT * FROM options";
    $result = $db->runQuery($query);

    // Parse options into key/value pairs
    foreach ($result as $row) {
        $options[$row['option_name']] = $row['option_value'];
    }


    // Check whether to check the debug mode checkbox
    if ($options['debug_mode'] == 1) {
        $debugModeChecked = "checked";
    }
    else {
        $debugModeChecked = "";
    }

    // Create Registration Form
    $main = "
        <form id='profile-form' action='$BASE_URL/csb-admin/index.php?option=settings' method='POST'>
            <input type='hidden' name='action' value='update'>
            <input type='hidden' name='debug_mode' value='0'>
            <label for='debug_mode'>Debug Mode:</label>
            <input type='checkbox' name='debug_mode' id='debug_mode' class='mt-4' $debugModeChecked>

            <input type='submit' value='Save Settings' class='btn btn-cq mt-4 right'>
        </form>
        $msg
        ";

    return array("main" => $main, "notes" => $notes);
}

function update ($db) {

    // Fetch old data to compare.
    $query = "SELECT * FROM options";
    $result = $db->runQuery($query);

    $changed = FALSE;

    // Parse options into key/value pairs
    foreach ($result as $row) {
        $options[$row['option_name']] = $row['option_value'];
    }

    $query = "";
    $msg = "";

    $tempDebugMode = $_POST['debug_mode'] == "on" ? "1" : "0";

    if ($tempDebugMode != $options['debug_mode']) {
        $changed = TRUE;
        $query .= "update options set option_value = ? where option_name = 'debug_mode';";
        $params_type = "s";
        $params[] = $tempDebugMode;
    }

    $msg = "<div class='clearfix' style='margin-bottom: 1em'></div>";
    if ($changed) {
        if ($db->update($query, $params_type, $params)) {
            $msg .= "<div class='alert-success text-center align-content-center'>Settings saved.</div>";
        } else {
            $msg .= "<div class='alert-danger text-center align-content-center'>Error saving settings.<div>";
        }
    } else {
        $msg .= "<div class='alert-info text-center align-content-center'>No changes made.</div>";
    }
      $text =  landing($db, $msg);
      return array("main" => $text['main'], "notes" => $text['notes']);
}
