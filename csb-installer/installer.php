<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:40 PM
 */

require_once("../csb-settings.php");
require_once("installer-functions.php");

// Valid types:
// primary, secondary, success, danger, warning, info, light, dark
// See: https://getbootstrap.com/docs/4.4/components/list-group/
function status_update($message, $type=null)
{
    $context_class = "list-group-item";
    if(!is_null($type))
    {
        $context_class .= " list-group-item-" . $type;
    }
    echo "<li class=\"" . $context_class . "\">" . $message . "</li>";
}

?>
<div class="container text-dark">
    <div class="row">
        <div class="col">
            <div class="card-deck">
                <div class="card">
                    <h4 class="card-header">Citizen Science Builder Installer</h4>
                    <div class="card-body">
                        <ul class="list-group">
<?php

status_update("You are running the Citizen Science Builder installer", "primary");

/* ----------------------------------------------------------------------
   Check if Database Exists
   ---------------------------------------------------------------------- */

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

$on_error = function ($log_messages, $frontend_messages, $data_added=true) use ($conn, $BASE_DIR)
{
    if(!is_null($conn))
    {
        if($data_added)
        {
            // TODO If we handle the table creation as migrations, we can roll back automatically.
            // Transaction rollback doesn't work here because MYSQL doesn't handle DDL in transactions consistently.
            status_update("An error occurred after the database was modified. You should drop and recreate the database before trying again.", "warning");
        }
        mysqli_close($conn);
    }

    unlink($BASE_DIR . "/csb-settings.php");
    foreach($log_messages as $log)
    {
        error_log($log);
    }
    foreach($frontend_messages as $message)
    {
        status_update($message, "danger");
    }
    echo "</ul></div></div></div></div></div></div>";
    die();
};

if ($conn->connect_error) {
    $on_error(
        ["Database connection failed.", "Server: " . $db_servername, "User: " . $db_username, "Database: " . $db_name],
        ["Connection to Database unsuccessful. Did you create the database '" . $db_name . "' on ".$db_servername."?"],
        false
    );
} else {
    status_update("Connected to database: " . $db_name . "<br/>", "success");
}

/* ----------------------------------------------------------------------
   Check if Tables Exist, throw error if it does, otherwise setup
   ---------------------------------------------------------------------- */

// Get files of all tables
$dir = __DIR__ . DIRECTORY_SEPARATOR . "tables" . DIRECTORY_SEPARATOR;
status_update("Descending into the depths of $dir", "info");

$created_tables = 0;
foreach (glob($dir . "*.sql") as $table) {
    error_log("Creating $table");
    $table_name = substr(str_replace($dir,"",strstr($table, ".sql", true)),2);

    // Read the whole file
    $fh = fopen($table,"r");
    $sql = fread($fh,filesize($table));
    fclose($fh);
    // Prepare tables depending on configuration
    //$sql = str_replace("|TABLE_PREFIX|", $table_prefix, $sql);

    if (create_table($conn,$sql)) {
        error_log("Created table " . $table_name);
        $created_tables += 1;
    } else {
        $on_error(
            [mysqli_errno($conn) . ": " . mysqli_error($conn)],
            ["Couldn't create table " . $table_name, "Check your server logs for details."],
            $created_tables > 0
        );
    }
}
status_update("Created " . $created_tables . " tables successfully!", "success");



/* ----------------------------------------------------------------------
   Generate Admin = CodeHerder account
   ---------------------------------------------------------------------- */

$username = "CodeHerder";
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$password = substr(str_shuffle($chars), 0, 12);
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Create the user with email from settings
$sql = "INSERT INTO users (id, name, email, password) VALUES (1, '" . $username . "', '" . $rescue_email . "', '" . $hashed . "');";
if (mysqli_query($conn, $sql) == FALSE) {
    $on_error([mysqli_errno($conn) . ": " . mysqli_error($conn)], ["Couldn't create admin user"]);
}

// Create their admin role
$sql = "INSERT INTO role_users (role_id, user_id) VALUES (8, 1);";
if (mysqli_query($conn, $sql) == FALSE) {
    $on_error([mysqli_errno($conn) . ": " . mysqli_error($conn)], ["Couldn't assign admin user to admin role"]);
}
status_update("Admin user set up", "success");

?>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <h4 class="card-header">Admin Information</h4>
                    <div class="card-body">
                        <p>Your admin username is: <code><?php echo $username ?></code></p>
                        <p>Your password is: <code><?php echo $password ?></code></p>
                        <p>Make sure you save this password in your password manager, it was randomly generated and won't be visible again!<p>
                        <h3>Setup complete</h3>
                        <a href="<?php echo $BASE_URL; ?>" class="btn btn-primary">Get started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/* ----------------------------------------------------------------------
   End Session
   ---------------------------------------------------------------------- */
mysqli_close($conn);
?>
