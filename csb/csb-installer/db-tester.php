<?php

header("Content-Type: application/json");

// Try connecting, @ will suppress warning / notice if the connection fails
$link = @mysqli_connect($_POST['db_servername'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name'], $_POST['db_port']);

// Check connection
if (!$link) {
    echo json_encode(array('result' => false, 'message' => "Connection failed: " . mysqli_connect_error(), 'code' => mysqli_connect_errno()));
    exit();
}

echo json_encode(array('result' => true));
exit();
