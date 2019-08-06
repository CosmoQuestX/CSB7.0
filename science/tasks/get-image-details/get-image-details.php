<?php

// Start with the form

?>


    <form id="GetImageId" action="<?php echo $BASE_URL;?>/science/">
        <input type="hidden" name="task" value="get-image-details">
        Enter Master Image Name: <input type="text" name="image_set_name" size="32"><input type="submit">
    </form>

<?php

// Check if an image was already submitted
    if (isset($_GET['image_set_name'])) {
        $name = $_GET['image_set_name'];

?>
	<div style="float:right; width:48%;">
	<img src="https://s3.amazonaws.com/cosmoquest/data/mappers/osiris/ImageDelivery_20190520/RAWIMAGES/<?php echo $name;?>" style="width:100%"> 
	</div>
        <div style="margin-top:30px; width: 50%;">
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
        $resultset = $db->runQuery($query);
	foreach($resultset as $result) {
		echo $result['id']." ";
		$images[] = $result['id'];
	}
	echo "</p>";


	// Get username and email of people who mapped this image
	echo "<p><strong>This master image was marked by: </strong></p>"; 
	$query = "SELECT distinct(users.name), users.email 
		  FROM users, image_users
		  WHERE (image_users.image_id = ";
	foreach($images as $image) {
		$query .= $image. " OR image_users.image_id =";
	}
	$query = substr($query, 0, -25);

	$query .= ") AND image_users.user_id = users.id ORDER BY users.name";
	$resultset = $db->runQuery($query);
	echo "<table style='color: black; font-size: 0.9em; width:100%;'>";
	foreach($resultset as $result) {
		echo "<tr><td>".$result['name']."</td><td>".$result['email']."</td></tr>";
	}
	echo "</table>";

        ?>
        </div>
        <?php
    }

