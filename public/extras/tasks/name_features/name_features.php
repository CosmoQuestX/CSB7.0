<?php
// naming_function
global $BASE_DIR, $BASE_URL, $adminFlag;


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

*/
// Check if an image was submitted. If not, get an image name


if (isset($_GET['markX'])) {
    echo "<h3>Debug Submission Data</h3>";
    echo "<table id='submission-data' style='border-collapse: collapse;'>";
    echo "<tr><th>Name</th><th>Value</th></tr>";
    foreach ($_GET as $varname => $varval) {
        echo "<tr><td>$varname</td><td>$varval</td></tr>";
    }
    echo "</table><style>#submission-data td,th {color:black; border: 1px black solid;}</style>";
}

if (!isset($_GET['image_set_name']) || $_GET['image_set_name'] == "") {
    ?>
    <form id="GetImageToName" action="<?php echo $BASE_URL; ?>/extras/">
        <input type="hidden" name="task" value="name_features">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>
    <?php
}


// Check if an image was already submitted
if (isset($_GET['image_set_name']) && $_GET['image_set_name'] !== "") {
    $name = basename(preg_replace("/[,;\\/]/", "", filter_input(INPUT_GET, 'image_set_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0)));

    ?>
    <div style="float:right; width:450px;">
        <img id="useImg" style="display:none;"
             src="https://s3.amazonaws.com/cosmoquest/data/mappers/osiris/ImageDelivery_20190520/RAWIMAGES/<?php echo $name; ?>"
             style="width:100%">
        <canvas id="canvas" width="450" height="450">
        </canvas>
    </div>

    <script>

        var canvas;
        var ctx;
        var rect;
        var mY;
        var mX;
        var locationMarker;
        var drawLocationMarker;
        var markLocation;
        var locationMarked = false;

        window.onload = function () {
            canvas = document.getElementById("canvas");
            ctx = canvas.getContext("2d");
            ctx.drawImage(document.getElementById("useImg"), 0, 0);
            animate();

            rect = canvas.getBoundingClientRect();


            canvas.addEventListener("mouseenter", function (e) {
                drawLocationMarker = true;
            });
            canvas.addEventListener("mouseleave", function (e) {
                drawLocationMarker = false;
            });

            canvas.addEventListener("mousemove", function (e) {
                mX = e.clientX - rect.left;
                mY = e.clientY - rect.top;
            });

            canvas.addEventListener("mousedown", function (e) {
                if (locationMarked) {
                    locationMarked = false;
                    document.getElementById("submit-button").disabled = true;
                }
            });

            canvas.addEventListener("mouseup", function (e) {
                markLocation = {X: mX, Y: mY};
                locationMarked = true;

                document.getElementById("markX").value = mX;
                document.getElementById("markY").value = mY;
                document.getElementById("submit-button").disabled = false;
            });

            locationMarker = new Image();
            locationMarker.src = "../csb-content/images/buttons/Loading.gif";
        }


        function animate() {
            window.requestAnimationFrame(animate);

            ctx.drawImage(document.getElementById("useImg"), 0, 0);

            // when mouse is on canvas, draw X centered on mouse position
            // ../csb-content/images/buttons/Mapping_tools-04.png
            if (locationMarked) {
                ctx.drawImage(locationMarker, markLocation.X - locationMarker.width / 2, markLocation.Y - locationMarker.height / 2);
            }

            if (!locationMarked && drawLocationMarker) {
                ctx.drawImage(locationMarker, mX - locationMarker.width / 2, mY - locationMarker.height / 2);
            }
            // when mouse is clicked on canvas, alert with {X:, Y:}

        }


    </script>

    <div style="margin-top:30px; width: 390px; /* 860-470 */">
        <?php
        echo "<H3>Image:<br>" . $name . "</H3>";

        // Get image set id
        $query = "SELECT * FROM image_sets WHERE name like '" . $name . "'";
        $resultset = $db->runQuery($query);
        $image_set_id = $resultset[0]['id'];

        // Get images in that set
        $query = "SELECT * FROM images WHERE image_set_id = $image_set_id";
        $resultSet = $db->runQuery($query);
        foreach ($resultSet as $result) {
            $images[] = $result['id'];
        }

        // Get username of people who mapped this image
        echo "<p><strong>Marked by: </strong>";
        $query = "SELECT distinct(users.name) 
            FROM users, image_users
            WHERE (image_users.image_id = ";
        foreach ($images as $image) {
            $query .= $image . " OR image_users.image_id =";
        }
        $query = substr($query, 0, -25); // remove the last, unneeded OR statement
        $query .= ") AND image_users.user_id = users.id ORDER BY users.name";
        $resultSet = $db->runQuery($query);
        $list = "";
        foreach ($resultSet as $result) {
            $list .= $result['name'] . ", ";
        }
        $list = substr($list, 0, -2);
        echo "$list</p>";


        // Setup form to propose name
        ?>
        <h2>Propose Feature Names</h2>
        <p class="instructions">Place the X on the feature you want to propose a name for, and then
            enter the name you want to propose. Please do not use adult language of any kind. Proposed names will be
            reviewed by the mission team and put forward for discussion in the community.</p>
        <form id="SubmitNames" action="<?php echo $BASE_URL; ?>/extras/">
            <input type="hidden" name="task" value="name_features">
            <div id="proposed_names">
                <p>Names will appear here</p>
            </div>
            <input type="hidden" name="image_set_name" id="image_set_name" value="<?php echo $name ?>">
            <input type="hidden" name="markX" id="markX" value="">
            <input type="hidden" name="markY" id="markY" value="">
            <label>Name: <input type="text" name="name"></label><br/>
            <label>Reason: <textarea name="reason"></textarea></label><br/>
            <button type="submit" id="submit-button">Submit Name</button>
        </form>
        <?php
        ?>
    </div>
    <?php
}

