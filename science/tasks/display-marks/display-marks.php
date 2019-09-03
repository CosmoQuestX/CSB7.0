<?php

// Start with the form

?>


    <form id="GetImageId" action="<?php echo $BASE_URL;?>/science/">
        <input type="hidden" name="task" value="display-marks">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>

<?php

// Check if an image was already submitted
if (isset($_GET['image_set_name'])) {
    $name = $_GET['image_set_name'];
    ?>
    <div style="margin-top:30px; width: 50%; float:left;">
        <?php
        echo "<p> The following details are found for <strong>".$_GET['image_set_name']."</strong></p>";

        // Get image set id
        $query  = "SELECT * FROM image_sets WHERE name like '".$_GET['image_set_name']."'";
        $resultset = $db->runQuery($query);
        $image_set_id = $resultset[0]['id'];
        echo "<p><strong>Image Set id:</strong> ", $image_set_id."<br/>";

        // Get images in that set
        echo "<p><strong>Including sub-images:</strong> ";
        $query = "SELECT * FROM images WHERE image_set_id = $image_set_id";
        $resultSet = $db->runQuery($query);
        $list = "";
        foreach($resultSet as $result) {
            $list .= $result['id'].", ";
            $images[] = array('id' => $result['id'], 'url' => $result['file_location'], 'name' => $result['name']);
        }
        $list = substr($list, 0, -2);
        echo "$list</p>";

        ?>
    </div>
    <div style="float:right; width:48%;">
        <img src="https://s3.amazonaws.com/cosmoquest/data/mappers/osiris/ImageDelivery_20190520/RAWIMAGES/<?php echo $name;?>" style="width:100%">
    </div>
    <div style="clear:both;"></div>

    <!-- Display individual images -->
    <?php
    $i = 1;
    $html = "";
    $script  = "";
    $scriptMarks = "";
    $script .= "<script>";
    $script .= "window.onload=function() {";

    foreach ($images as $image) {
       $canvas = "canvas".$i;
       $science = "science".$i;

       $html .= "<h5>". $image['name']." ".$canvas."</h5>";
       $html .= "<img id='". $science ."' src='".$image['url']."'' style='display: none;'>";
       $html .= "<canvas id='$canvas' width='450' height='450'></canvas>";

        $script .= ' var c'.$i.' = document.getElementById("'.$canvas.'");';
        $script .= ' var ctx'.$i.' = c'.$i.'.getContext("2d");';
        $script .= ' var img'.$i.' = document.getElementById("'.$science.'");';
        $script .= ' ctx'.$i.'.drawImage(img'.$i.', 0, 0);';

        $query = "SELECT id, details FROM marks WHERE type = 'boulder' AND image_id = ".$image['id'];
        $results = $db->runQuery($query);
        foreach ($results as $result) {
            $details = json_decode($result['details'], TRUE);
            $x1 = $details['points'][0]['x'];
            $y1 = $details['points'][0]['y'];
            $x2 = $details['points'][1]['x'];
            $y2 = $details['points'][1]['y'];
            $scriptMarks .= 'drawBoulder('.$x1.', '.$y1.', '.$x2.', '.$y2.', "'.$canvas.'"); ';
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