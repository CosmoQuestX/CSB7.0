<?php
// Start with the form

// Lot for $_POST variables and output file if variables exist


?>


<!-- <form id="DataFormat" action="<?php echo $BASE_URL;?>/science/index.php?task=download-data"> -->
<form id="DataFormat" action="">
    <input type="hidden" name="task" value="download-data">
    <input type="hidden" name="app_id" value="21">
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
    <input type="radio" name="combined" value="TRUE">   Combined Marks &nbsp; &nbsp;
    <input type="radio" name="combined" value="FALSE"  checked>          Individual Marks
    </p>

    <input type="button" value="download" onClick='dataFunction(DataFormat.app_id.value,DataFormat.combined.value);'>
</form>

<div id='results'></div>

<?php

?>


<?php
if (isset($_GET) && isset($_GET['app_id'])) {
    if ($_GET['combined'] === TRUE) {
        echo "<p>Combined data is not currently available.</p>";
    } else {
        echo "Your data should be downloading.";
    }
}


?>

<script>
    function dataFunction(app_id, combined) {
        var myWindow = window.open("/science/tasks/download-data/output.php?app_id=" + app_id + "&combined=" + combined, "", "width=800,height=100");
        return
    }
</script>
