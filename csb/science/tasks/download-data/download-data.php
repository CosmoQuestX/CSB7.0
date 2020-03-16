<?php
// Start with the form

if (isset($_GET['request']) && $_GET['request'] == 'start') {
    $flag = TRUE;
} else {
    //TODO Add database check to look for existing files
    $flag = FALSE;
}

// We should get the user info to retreive the email address
$user = $db->getUser($_SESSION['user_id']);

?>

<h3> Available Downloads</h3>
<p>
    <?php
    // See if there are existing downloads
    $downloads = $db->getDownloads($_SESSION['user_id']);
    if ($downloads != FALSE) {

        foreach ($downloads as $download) {
            echo "<a href='" . $download['link'] . "'>" . $download['name'] . "</a><br/>";
        }
    } else {
        echo "Nothing available.<br/>";
    }

    // Check if there is a request, process it
    if (isset($_GET['request']) && $_GET['request'] == 'start') {
        echo "New download starting...";
        if ($_GET['combined'] == "TRUE") {  // throw an error if combined
            echo "ERROR: Combined data not currently available. Download stopped.";
        } else { // otherwise start the download
            // Note download in DB and get row id
            $download_id = $db->submitDownload($_SESSION['user_id']);
            // Execute the download
            $command = "php " . $BASE_DIR . 'science/tasks/download-data/output_individual.php ' . $_GET['app_id'] . ' ' . $_GET['dataRange'] . ' ' . $_GET['email'] . ' ' . $download_id;
            exec($command . " > " . $BASE_DIR . "temp/output-errors.log>&1 & echo $1");
        }
    }

    ?>
</p>

<form id="DataFormat" method="GET" action="<?php echo $BASE_URL; ?>/science/index.php?task=download-data">
    <input type="hidden" name="task" value="download-data">
    <input type="hidden" name="request" value="start">
    <h3>Select Data Download Options</h3>

    <!-- Select Project -->
    <p><strong>Project:</strong><br/>
        <?php
        $query = "SELECT title, id FROM applications WHERE active = 1";
        $results = $db->runQuery($query);
        if ($results === FALSE)
        echo "No applications found";
        else {
        echo "<select name=\"app_id\">";
        foreach ($results as $result) {
            $id = $result['id'];
            $title = $result['title'];
            if ($id == 21) // CQ HACK - REMOVE PUBLIC
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
                <option value="heroes">Hero User Data</option>
                <?php
                $i = 1;
                $date_value = date("Y-m-d", strtotime("May 22, 2019"));
                $date_name = "Week $i, " . date("M j", strtotime($date_value)); // Thanks to @ChrisBartow for format help

                while ($date_value < date("Y-m-d")) {
                    echo "<option value='$date_value'>$date_name</option>";
                    $i++;
                    $date_value = date("Y-m-d", strtotime("$date_value +7 day"));
                    $date_name = $date_name = "Week $i, " . date("M j", strtotime($date_value));
                }

                ?>
            </select>
        </strong></p>
    <?php

    }
    ?>


    <!-- Individual / Combined -->
    <p><strong>Kind of Marks</strong><br/>
        <input type="radio" name="combined" value="TRUE"> Combined Marks &nbsp; &nbsp;
        <input type="radio" name="combined" value="FALSE" checked> Individual Marks
    </p>

    <!-- Get their email-->
    <p><strong>Email</strong><br/>
        These files are large. We will email you when it is done being generated.<br/>
        <input type="text" name="email" value="<?php echo $user['email']; ?>">
    </p>

    <input type="submit" value="submit request"
           onClick='dataFunction(DataFormat.url.value,DataFormat.app_id.value,DataFormat.combined.value,DataFormat.dataRange.value,DataFormat.email.value,);'>
</form>

<div id='results'></div>

<?php
?>

<script>
    function dataFunction(url, app_id, combined, page, data, email) {
        //window.open(url+"?app_id=" + app_id + "&combined=" + combined + "&data=" + data + "&email=" + email, "", "width=600,height=600");
        document.getElementById('results').innerHTML = "<p>Thank you! We'll email you as soon as your download is ready</p>"
    }
</script>

