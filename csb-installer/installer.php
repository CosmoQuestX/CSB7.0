<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:40 PM
 */

require_once("../csb-settings.php");
require_once("installer-functions.php");

echo "You are running the Citizen science Builder installer <br>";


/* ----------------------------------------------------------------------
   Check if Database Exists
   ---------------------------------------------------------------------- */

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    unlink($BASE_DIR . "/csb-settings.php");
    die("Connection to Database Unsuccessful. Did you create the database '" . $db_name . "' on ".$db_servername."?");
} else {
    echo "Connected to database: " . $db_name . "<br/>";
}

/* ----------------------------------------------------------------------
   Check if Tables Exist, throw error if it does, otherwise setup
   ---------------------------------------------------------------------- */

// Get files of all tables
$dir = __DIR__ . DIRECTORY_SEPARATOR . "tables" . DIRECTORY_SEPARATOR;
print "Descending into the depths of $dir <br />\n";

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
    } else {
        error_log(mysqli_errno($conn) . mysqli_error($conn));
        mysqli_close($conn);
        error_log("Couldn't create table " . $table_name . "");
        unlink($BASE_DIR . "/csb-settings.php");
        die("Error in creating tables, see logfile for further information!");
    }
}



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
    mysqli_close($conn);
    die("Couldn't create admin user <br/>");
}

// Create their admin role
$sql = "INSERT INTO role_users (role_id, user_id) VALUES (8, 1);";
if (mysqli_query($conn, $sql) == FALSE) {
    mysqli_close($conn);
    die("Couldn't create admin role <br/>");
}

// Tell them their info
echo "Your Admin username is: CodeHerder</br>";
echo "Your password is: " . $password . "<br/>";


/* ----------------------------------------------------------------------
   End Session
   ---------------------------------------------------------------------- */

mysqli_close($conn);