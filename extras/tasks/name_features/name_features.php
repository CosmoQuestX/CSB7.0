<?php

/* ----------------------------------------------------------
   Goal: Name Images, with the user entering the name of
   an image that is in the database
    SETUP:
        add application Bennu_Naming

    FUNCTION:
    1) Click on spot to mark
    2) Place a cosmoquest X at the spot
    3) Confirm location or adjust - ask boulder / crater (get from mission)
    4) Get form with Name (text field name 255, textarea why limit to 1000char)
    5) confirm & submit
    6) submit to db
            image_users db with image_id, user_id, application_id
            marks x, y, column 'type' = 'name' (instead of rock or crater) as JSON in details (x, y, type, name, why)



// Check if an image was submitted. If not, get an image name
if (!isset($_GET['image_set_name'])) {
    ?>
    <form id="GetImageToName" action="<?php echo $BASE_URL; ?>/extras/">
        <input type="hidden" name="task" value="name_features">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>
    <?php
}

// Check if an image was already submitted
if (isset($_GET['image_set_name'])) {
    $name = $_GET['image_set_name'];

    ?>
    <div style="float:right; width:450px;">
        <img id = "useImg" style="display:none;" src="https://s3.amazonaws.com/cosmoquest/data/mappers/osiris/ImageDelivery_20190520/RAWIMAGES/<?php echo $name;?>" style="width:100%">
        <canvas id="canvas" width="450" height="450">
        </canvas>
    </div>

    <script>
        window.onload = function () {
            var c = document.getElementById("canvas");
            var ctx = c.getContext("2d");
            var img = document.getElementById("useImg");
            ctx.drawImage(img, 0, 0);
        }

    </script>

    <div style="margin-top:30px; width: 390px; /* 860-470 */">
        <?php
        echo "<H3>Image:<br>".$_GET['image_set_name']."</H3>";

        // Get image set id
        $query  = "SELECT * FROM image_sets WHERE name like '".$_GET['image_set_name']."'";
        $resultset = $db->runQuery($query);
        $image_set_id = $resultset[0]['id'];

        // Get images in that set
        $query = "SELECT * FROM images WHERE image_set_id = $image_set_id";
        $resultSet = $db->runQuery($query);
        foreach($resultSet as $result) {
            $images[] = $result['id'];
        }

        // Get username of people who mapped this image
        echo "<p><strong>Marked by: </strong>";
        $query = "SELECT distinct(users.name) 
            FROM users, image_users
            WHERE (image_users.image_id = ";
        foreach($images as $image) {
            $query .= $image. " OR image_users.image_id =";
        }
        $query = substr($query, 0, -25); // remove the last, unneeded OR statement
        $query .= ") AND image_users.user_id = users.id ORDER BY users.name";
        $resultSet = $db->runQuery($query);
        $list = "";
        foreach($resultSet as $result) {
            $list .= $result['name'].", ";
        }
        $list = substr($list, 0, -2);
        echo "$list</p>";


        // Setup form to propose name
        ?>
        <h2>Propose Feature Names</h2>
        <p class="instructions">Click and drag a circle around the feature you want to propose a name for, and then
            enter the name you want to propose. Please do not use adult language of any kind. Proposed names will be
            reviewed by the mission team and put forward for discussion in the community.</p>
        <form id="SubmitNames" action="<?php echo $BASE_URL;?>/extras/">
            <input type="hidden" name="task" value="name_features">
            <div id="proposed_names">
                <p>Names will appear here</p>
            </div>
        </form>
        <?php
        ?>
    </div>
    <?php
}

