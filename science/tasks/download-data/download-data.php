<?php
// Start with the form

// Lot for $_POST variables and output file if variables exist


?>


<form id="DataFormat" action="<?php echo $BASE_URL;?>/science/download-data/download-data.php">

    <h3>Select Data Download Options</h3>

    <!-- Select Project -->
    <p><strong>Project:</strong><br/>
    <?php
    $query = "SELECT title, id FROM applications WHERE active = 1";
    $results = $db->runQuery($query);

    if ($results === FALSE ) echo "No applications found";
    else {
        foreach ($results as $result) {
            ?>
            <select name="app_id">
                <option value="<?php echo $result['id'];?>"><?php echo $result['title'];?></option>
            </select></p>
            <?php
        }
    }
    ?>


    <!-- Individual / Combined -->
    <p><strong>Kind of Marks</strong><br/>
    <input type="radio" name="combined" value="TRUE" checked>   Combined Marks &nbsp; &nbsp;
    <input type="radio" name="combined" value="FALSE">          Individual Marks
    </p>

    <input type="submit" value="go">




</form>
