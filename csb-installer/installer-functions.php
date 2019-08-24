<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 2:37 PM
 */

/* ----------------------------------------------------------------------
   Check if Table Exists
   ---------------------------------------------------------------------- */

    function table_exists( $conn, $table) {
        $val = mysqli_query($conn, "select 1 from '". $table . "'");

        if($val !== FALSE) {
            return 1;
        }
        else {
            return 0;
        }
    }

/* ----------------------------------------------------------------------
   Create Table from Structure
   ---------------------------------------------------------------------- */

    function create_table( $conn, $structure) {
        if (mysqli_query($conn, $structure) !== FALSE) {
            return 1;
        }
        else {
            return 0;
        }
    }
    
    /* ----------------------------------------------------------------------
     Check if a variable is set
     ---------------------------------------------------------------------- */
    
    function checksetvar($input) {
        if(!isset($_POST["{$input}"])) { die("This should not happen, but {$input} is not set"); }
    }