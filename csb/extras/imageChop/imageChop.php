<?php
// create a form to upload an image
echo "<form action='imageChop.php' method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='fileToUpload' id='fileToUpload'>";
echo "<input type='submit' value='Upload Image' name='submit'>";
echo "</form>";

// if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get the file name
    $target_file = "uploads/".basename($_FILES["fileToUpload"]["name"]);
    echo "target_file: $target_file<br>";
//    // get the file extension
//    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
//    // check if the file is an image
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".<br>";
//        // check if the file already exists
//        if (file_exists($target_file)) {
//            echo "Sorry, file already exists.<br>";
//        } else {
//            // check the file size
//            if ($_FILES["fileToUpload"]["size"] > 5000000) {
//                echo "Sorry, your file is too large.<br>";
//            } else {
//                // check the file type
//                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
//                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
//                } else {
//                    // move the file to the uploads directory
//                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//                        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.<br>";
//                        // display the image
//                        echo "<img src='$target_file' alt='uploaded image'>";
//                    } else {
//                        echo "Sorry, there was an error uploading your file.<br>";
//                    }
//                }
//            }
//        }
    } else {

        echo "Another error occurred.<br>";
    }

    //Create the image based on the file type
    switch ($imageFileType) {
        case "jpg":
        case "jpeg":
            $image = imagecreatefromjpeg($target_file);
            break;
        case "png":
            $image = imagecreatefrompng($target_file);
            break;
        case "gif":
            $image = imagecreatefromgif($target_file);
            break;
        default:
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            return;
    }

    // chop the image into 450x450 pieces and save them in the uploads directory
    $width = imagesx($image);
    $height = imagesy($image);
    $pieceWidth = 450;
    $pieceHeight = 450;
    $numPieces = 0;
    for ($y = 0; $y < $height; $y += $pieceHeight) {
        for ($x = 0; $x < $width; $x += $pieceWidth) {
            $numPieces++;
            $piece = imagecreatetruecolor($pieceWidth, $pieceHeight);
            imagecopy($piece, $image, 0, 0, $x, $y, $pieceWidth, $pieceHeight);
            imagepng($piece, "uploads/piece$numPieces.png");
            imagedestroy($piece);
        }
    }
}
