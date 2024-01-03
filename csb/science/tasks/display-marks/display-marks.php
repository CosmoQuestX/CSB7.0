<?php

// Start with the form

?>


    <form id="GetImageId" action="<?php echo $BASE_URL; ?>science/">
        <input type="hidden" name="task" value="display-marks">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>

<?php

// Check if an image was already submitted
if (isset($_GET['image_set_name']) && $_GET['image_set_name'] !== "") {
    $name = basename(preg_replace("/[,;\\/]/", "", filter_input(INPUT_GET, 'image_set_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0)));
    ?>

    <?php
    echo "<p> The following details are found for <strong>" . $name . "</strong></p>";

    // Get image set id
    $query = "SELECT * FROM image_sets WHERE name like '" . $name . "'";
    $resultset = $db->runQuery($query);
    $image_set_id = $resultset[0]['id'];
    echo "<p><strong>Image Set id:</strong> ", $image_set_id . "<br/>";

    // Get images in that set
    echo "<p><strong>Including sub-images:</strong> ";
    $query = "SELECT * FROM images WHERE image_set_id = $image_set_id";
    $resultSet = $db->runQuery($query);
    $list = "";
    foreach ($resultSet as $result) {
        $list .= $result['id'] . ", ";
        $images[] = array(
            'id' => $result['id'],
            'url' => $result['file_location'],
            'name' => $result['name'],
            'details' => $result['details']);
    }
    $list = substr($list, 0, -2);
    echo "$list</p>";

    ?>

    <img id="science0"
         src="https://s3.amazonaws.com/cosmoquest/data/mappers/osiris/ImageDelivery_20190520/RAWIMAGES/<?php echo $name; ?>"
         style="display: none;">
    <h3>Mosaic</h3>
    <canvas id="canvasTest" width='1024' height='1024'></canvas>
    <h3>Marked Original</h3>
    <canvas id="canvas0" width='1024' height='1024'></canvas>
    <!-- Display individual images -->
    <?php
    $i = 1;
    $html = "";
    $script = "";
    $scriptMarks = "";
    $script .= '<script>';
    $script .= "window.onload=function() {
                    var c = document.getElementById(\"canvas0\");
                    var ctx = c.getContext(\"2d\");
                    var img0 = document.getElementById(\"science0\");
                    ctx.drawImage(img0, 0, 0);

                    var cTest = document.getElementById(\"canvasTest\");
                    var ctxTest = cTest.getContext(\"2d\");
                    var imgTest = document.getElementById(\"science0\");
                    ctxTest.drawImage(imgTest, 0, 0);";

    foreach ($images as $image) {
        $canvas = "canvas" . $i;
        $science = "science" . $i;

        $origin = json_decode($image['details'], TRUE);

        echo "<br/><br/>";

        $html .= "<h5>" . $image['name'] . " " . $canvas . "</h5>";
        $html .= "<img id='" . $science . "' src='" . $image['url'] . "'' style='display: none;'>";
        $html .= "<canvas id='$canvas' width='450' height='450'></canvas>";

        $script .= ' var c' . $i . ' = document.getElementById("' . $canvas . '");';
        $script .= ' var ctx' . $i . ' = c' . $i . '.getContext("2d");';
        $script .= ' var img' . $i . ' = document.getElementById("' . $science . '");';
        $script .= ' ctx' . $i . '.drawImage(img' . $i . ', 0, 0);';

        $script .= ' var cTest' . $i . ' = document.getElementById("canvasTest");';
        $script .= ' var ctxTest' . $i . ' = cTest' . $i . '.getContext("2d");';
        $script .= ' var imgTest' . $i . ' = document.getElementById("' . $science . '");';
        $script .= ' ctxTest' . $i . '.drawImage(imgTest' . $i . ', ' . $origin['x'] . ', ' . $origin['y'] . ');';


        $query = "SELECT id, details FROM marks WHERE type = 'boulder' AND image_id = " . $image['id'];
        $results = $db->runQuery($query);
        foreach ($results as $result) {
            $details = json_decode($result['details'], TRUE);
            $x1 = $details['points'][0]['x'];
            $y1 = $details['points'][0]['y'];
            $x2 = $details['points'][1]['x'];
            $y2 = $details['points'][1]['y'];
            $scriptMarks .= 'drawBoulder(' . $x1 . ', ' . $y1 . ', ' . $x2 . ', ' . $y2 . ', "' . $canvas . '"); ';

            $x1 += $origin['x'];
            $x2 += $origin['x'];
            $y1 += $origin['y'];
            $y2 += $origin['y'];
            $scriptMarks .= 'drawBoulder(' . $x1 . ', ' . $y1 . ', ' . $x2 . ', ' . $y2 . ', "canvas0"); ';
        }

        $i++;
    }

    $script .= 'function drawBoulder(x1, y1, x2, y2, canvas) {
                var c = document.getElementById(canvas);
                var ctx = c.getContext("2d");
                ctx.lineWidth = 3;
                ctx.strokeStyle = "#FFE87C";
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
              } ';

    $script .= $scriptMarks;

    $script .= "}</script>;";
    echo $html;
    echo $script;

}
