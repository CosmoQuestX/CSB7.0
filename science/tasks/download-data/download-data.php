<?php
// Start with the form

// Lot for $_POST variables and output file if variables exist


?>


<form id="DataFormat" action="<?php echo $BASE_URL;?>/science/index.php?task=download-data">
    <input type="hidden" name="task" value="download-data">

    <h3>Select Data Download Options</h3>

    <!-- Select Project -->
    <p><strong>Project:</strong><br/>
    <?php
    $query = "SELECT title, id FROM applications WHERE active = 1";
    $results = $db->runQuery($query);

    if ($results === FALSE ) echo "No applications found";
    else {
        echo "<select name=\"app_id\">";
        foreach ($results as $result) {
            $id = $result['id'];
            $title = $result['title'];
            if($id == 21) 
                echo "<option value='$id' SELECTED>$title</option>";
            else
                echo "<option value='$id'>$title</option>";
            echo "</select></p>";
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

<?php
if (isset($_GET) && isset($_GET['app_id'])) {
    print_r($_GET);
}


?>
