<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 2:37 PM
 */

/**
 * Check if Table Exists
 * @param resource $conn    The database connection resource
 * @param string $table     The name of the table to be checked
 * @return boolean          True if table exists, otherwise false
 */
function table_exists($conn, $table)
{
    $val = mysqli_query($conn, "select 1 from '" . $table . "'");

    if ($val !== false) {
        return true;
    } else {
        return false;
    }
}


/**
 * Create Table from Structure
 * @param resource $conn        The database connection resource
 * @param string $sql           The SQL to create a table
 * @return boolean              True is successful, otherwise false
 */
function create_table($conn, $sql)
{
    if (mysqli_query($conn, $sql) !== false) {
        return true;
    } else {
        return false;
    }
}


/**
 * Checks whether PHP is at least <version>
 * @param string $rq_version    The required PHP version
 * @return boolean              True if new enough, false when not.
 */
function checkForPHP($min_version, $rec_version)
{
    /* Minimal PHP version is hard defined in the installer
     * TODO Maybe it would be nice to not hard-code requirements
     * See if we can resolve the new-style PHP_VERSION_ID constant
     * That appeared with 5.2.7, so if it doesn't exist then
     * the PHP version is not suitable
     * @param int $min_version The minimal version
     * @param int $rec_version  The recommended version
     * @return int (1|2) supported / outdated php or false if PHP is too old
     * */
    if (! defined('PHP_VERSION_ID')) {
        $rt = false;
    } // if our PHP is newer than the requirement
    elseif (PHP_VERSION_ID > $rec_version) {
        $rt = 1;
    } // If php is new enough to run but is an outdated version
    elseif (PHP_VERSION_ID > $min_version && PHP_VERSION_ID < $rec_version) {
        $rt = 2;
    } // If PHP is newer than 5.2.7 but still too old
    else {
        $rt = false;
    }
    return $rt;
}


/**
 * Checks whether a PHP extension is loaded
 * @param string $extension     The PHP extension to be queried
 * @return boolean              True if new enough, false when not.
 */
function checkForExtension($extension)
{
    /* Extensions are hard defined in the installer
     * TODO Maybe it would be nice to not hard-code requirements
     */
    if (extension_loaded($extension)) {
        $rt = true;
    }
    else {
        $rt = false;
    }
    return $rt;
}


/**
 * Checks whether a PHP class can be loaded
 * @param string $class     The PHP extension to be queried
 * @return boolean          True if new enough, false when not.
 */
function checkForClass($class)
{
    /* Optional classes are hard defined in the installer
     * TODO Maybe it would be nice to not hard-code requirements
     */

    if ($class == "Mail") {
        require_once 'System.php';
        if(class_exists('System', false)) {
            return true;
        } else {
            return false;
        }
    } else {
        @include "$class.php";

        if (class_exists($class)) {
            $classInstance = new $class();
            return $classInstance->isValid();
        } else {
            $rt = false;
        }
        return $rt;
    }
}
