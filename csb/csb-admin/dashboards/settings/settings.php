<?php

function Main () {
    // Keep the duplicated code to check for form changes made above
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
        <h3 class='font-weight-bold'>Admin Settings</h3>
        <form id='profile-form' action='".$_SERVER['REQUEST_URI']."' method='POST'>
            <input type='hidden' name='debug_mode' value='0'>
            <label for='debug_mode'>Debug Mode:</label>
            <input type='checkbox' name='debug_mode' id='debug_mode' class='mt-4' $debugModeChecked>

            <input type='submit' value='Save Settings' class='btn btn-cq mt-4 right'>
        </form>
        ";

    if (isset($saved) && $saved) {
        $main .= "<div class='text-success'>Settings saved!</div>";
        unset($saved);
    }
    elseif (isset($saved) && !$saved) {
        $main .= "<div class='text-danger'>Error saving settings!</div>";
        unset($saved);
    }

    $notes = "
        <h5 class='font-weight-bold'>Some Title Here</h5>
        <p>
        This should contain important info at some point.
        </p>
        ";

    return array("main" => $main, "notes" => $notes);
}

function Update () {
    // Fetch old data to compare.
    $query = "SELECT * FROM options";
    $result = $db->runQuery($query);

    $changed = FALSE;

    // Parse options into key/value pairs
    foreach ($result as $row) {
        $options[$row['option_name']] = $row['option_value'];
    }

    $query = "";

    $tempDebugMode = $_POST['debug_mode'] == "on" ? "1" : "0";

    if ($tempDebugMode != $options['debug_mode']) {
        $changed = TRUE;
        $query .= "update options set option_value = ? where option_name = 'debug_mode';";
        $params_type = "s";
        $params[] = $tempDebugMode;
    }

    if ($changed) {
        if ($db->update($query, $params_type, $params)) {
            $saved = TRUE;
        } else {
            $saved = FALSE;
        }
        return $saved;
    }
    return null; // How did you get here?
}
