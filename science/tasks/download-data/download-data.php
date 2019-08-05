<?php
// Start with the form

// Lot for $_POST variables and output file if variables exist


?>


<form id="DataFormat" action="<?php echo $BASE_URL;?>/science/download-data/download-data.php">

    <!-- Select Project -->
    <?php
    $query = "SELECT name, id FROM applications WHERE active = 1";
    $results = $db->runQuery($query);

    if ($results === FALSE ) echo "No applications found";
    else {
        foreach ($results as $result) {
            echo $result['title'] . "<br/>";
        }
    }
    ?>


    <!-- Individual / Combined -->







</form>
