<?php

// Start with the form

?>


    <form id="GetImageId" action="<?php echo $BASE_URL;?>/science/get-image-details/get-image-details.php">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>

<?php

// Check if an image was already submitted
    if (isset($_GET['image_set_name'])) {
        $name = $_GET['image_set_name'];

        ?>
        <div style="margin-top:30px">
        <?php
        echo "<p> The following details are found for <strong>".$_GET['image_set_name']."</strong></p>";

        // Get image set id
        $query  = "SELECT * FROM image_sets WHERE id=4209";

        $resultset = $db->runQuery($query);
        $row = mysqli_fetch_assoc($result);
        echo "Image id: ", $row['id'];
        ?>
        </div>
        <?php
    }

