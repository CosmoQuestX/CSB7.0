<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:40 PM
 */


/* ----------------------------------------------------------------------
   If we don't have a readable configuration, we shouldn't even try to 
   install anything!
   ---------------------------------------------------------------------- */

if (!is_readable("../csb-settings.php")) { die ("Configuration not readable!"); }
// Another alternative to die-ing would be to jump back to installer.php. 

require_once("../csb-settings.php");
require_once("installer-functions.php");

/* ----------------------------------------------------------------------
 Let's set a default theme so the installer doesn't look too boring.
 ---------------------------------------------------------------------- */

$page_title = "CSB Installer";

$THEME_DIR = $BASE_DIR . "csb-themes/default/";
$THEME_URL = $BASE_URL . "csb-themes/default/";

global $THEME_URL, $THEME_DIR; $page_title;

require_once($BASE_DIR . "/csb-content/template_functions.php");
loadHeader();

/* ---------------------------------------------------------------------
 The default header shows the login div id user on the top right.
 So since we definitely don't have a user yet, hide the div.
 --------------------------------------------------------------------- */

echo "<script type=\"text/javascript\" language=\"JavaScript\">";
echo "document.getElementById(\"user\").style.display = \"none\";";
echo "</script>";


echo "You are running the Citizen science Builder installer <br>";


/* ----------------------------------------------------------------------
   Check if Database Exists
   ---------------------------------------------------------------------- */

    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection to Database Unsuccessful. Did you create the database '" . $db_name . "''?");
    }
    else {
        echo "Connected to database: " . $db_name . "<br/>";
    }

/* ----------------------------------------------------------------------
   Check if Tables Exist, throw error if it does, otherwise setup
    TODO Number all the tables sensibly
   ---------------------------------------------------------------------- */

    // Get files of all tables
    $dir = "tables";
    $tables = array_diff(scandir($dir), array('..', '.'));

    foreach ($tables as $table) {

        // Read in each table
        $inserts = FALSE;
        require_once ("tables/".$table);
        $table = substr($table, 0, -4);

        if (create_table($conn, $structure)) {
            echo "created table " . $table . "<br/>";
        } else {
            echo mysqli_error($conn) . "<br/>";
            mysqli_close($conn);
            die("Couldn't create table ". $table . "<br/>");
        }

        // If there are any defaults, insert them
        if ($inserts !== FALSE) {
            foreach ($inserts as $insert) {
                if (mysqli_query($conn, $insert) == FALSE) {
                    mysqli_close($conn);
                    die("FAILED: $insert <br/>");
                }

            }
        }
    }


/* ----------------------------------------------------------------------
   Generate Admin = CodeHerder account
   ---------------------------------------------------------------------- */

    $username = "CodeHerder";
    $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, 12);
    $hashed   = password_hash($password, PASSWORD_DEFAULT);

    // Create the user with email from settings
    $sql = "INSERT INTO users (id, name, email, password) VALUES (1, '" . $username . "', '". $rescue_email . "', '" . $hashed . "');";
    if (mysqli_query($conn, $sql) == FALSE) {
        mysqli_close($conn);
        die("Couldn't create admin user <br/>");
    }

    // Create their admin role
    $sql = "INSERT INTO role_users (role_id, user_id) VALUES (1, 1);";
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