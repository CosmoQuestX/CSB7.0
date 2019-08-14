<?php
// Start with the form
// Lot for $_POST variables and output file if variables exist
?>


<!-- <form id="DataFormat" action="<?php echo $BASE_URL;?>/science/index.php?task=download-data"> -->
<form id="DataFormat" action="">

    <input type="hidden" name="task" value="download-data">
    <input type="hidden" name="page" value="0">
    <input type="hidden" name="url" value="<?php echo $BASE_URL; ?>science/tasks/download-data/output.php">
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
                if($id == 21) // CQ HACK - REMOVE PUBLIC
                    echo "<option value='$id' SELECTED>$title</option>";
                else
                    echo "<option value='$id'>$title</option>";
                echo "</select></p>";
            }

            // CQ HACK - REMOVE PUBLIC
            // Output each week, weeks start on Wednesdays (who knew)
            // Start with May 22, 2019

            $date = date_create('2019-05-22 00:00:00');
            ?>
            <p><strong>Data Range
                <select name='dataRange'>
                    <?php
                    $i = 1;
                    $date_value = date("Y-m-d", strtotime("May 22, 2019"));
                    $date_name  = "Week $i, ". date("M j", strtotime($date_value)); // Thanks to @ChrisBartow for format help

                    while ($date_value < date("Y-m-d")) {
                        echo "<option value='$date_value'>$date_name</option>";
                        $i++;
                        $date_value = date("Y-m-d", strtotime("$date_value +7 day"));
                        $date_name = $date_name  = "Week $i, ". date("M j", strtotime($date_value));
                    }


                    ?>
                </select>
            </strong></p>
            <?php

        }
        ?>


        <!-- Individual / Combined -->
    <p><strong>Kind of Marks</strong><br/>
        <input type="radio" name="combined" value="TRUE">   Combined Marks &nbsp; &nbsp;
        <input type="radio" name="combined" value="FALSE"  checked>          Individual Marks
    </p>

       <!-- Get their email-->
    <p><strong>Email</strong><br/>
        These files are large. We will email you when it is done being generated.<br/>
    <input type="text" name="email" value="you@email.com">
    </p>

    <input type="button" value="submit request" onClick='dataFunction(DataFormat.url.value,DataFormat.app_id.value,DataFormat.combined.value,DataFormat.dataRange.value,DataFormat.email.value,);'>
</form>

<div id='results'></div>

<?php
?>

<script>
    function dataFunction(url, app_id, combined, page, data, email) {
        window.open(url+"?app_id=" + app_id + "&combined=" + combined + "&data=" + data + "&email=" + email, "", "width=300,height=300");
        return
    }
</script>

