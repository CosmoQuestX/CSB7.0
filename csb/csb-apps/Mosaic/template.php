<?php

// create a template that shows an image on the left in a canvas and has options on the right to select options yes or no
// the options are: Yes, No

// Connect to the database
global $db_servername, $db_username, $db_password, $db_name, $db_port;
$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

// select a random image from the database that has done = 0
$query = "SELECT * FROM images WHERE done = 0 ORDER BY RAND() LIMIT 1";
$result = $db->runQuery($query);
$image = $result[0]['file_location'];
$id = $result[0]['id'];
?>

<div id="test">Barney</div>
<div class="container px-0">
    <div class="row">
        <div class="col-6">
            <div id="project_canvas" width="450" height="450">
                <img id="currentImage" src="" data-image-id="0">
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <h2>Options</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-primary" value="perfect">Perfect Image</button>
                    <button class="btn btn-primary" value="flawed">Flawed Mosaic</button>
                    <button class="btn btn-primary" value="black">Just Black</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initial Image Load
    loadImage();

    // Function to Load Image
    function loadImage() {
        fetch('csb-apps/Mosaic/writeMosaicData.php') // Make the request to your PHP file
        // put the response from the php file into the div testDiv
        .then(response => response.json())
        .then(data => {
            document.getElementById('currentImage').src = data.imageUrl;
            document.getElementById('currentImage').setAttribute('data-image-id', data.imageId);
            document.getElementById('test').innerHTML = data.notes;
        });

        // Add event listener to listen for clicks on a button
        const buttons = document.querySelectorAll('.btn-primary');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const buttonValue = button.value;
                const imageId = document.getElementById('currentImage').getAttribute('data-image-id');
                const userId = <?php echo $_SESSION['user_id']; ?>;

                // send the data to the php file as variables
                fetch('csb-apps/Mosaic/writeMosaicData.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: 'buttonValue=' + buttonValue + '&imageId=' + imageId + '&userId=' + userId
                });

                // load the next image
                loadImage();

            });
        });
    }
</script>
