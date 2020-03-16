<?php

header("Content-Type: application/json");

// Build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));

// Try connecting, @ will suppress warning / notice if the connection fails
$link = @mysqli_connect($v->db_servername, $v->db_username, $v->db_password, $v->db_name);

// Check connection
if (!$link) {
    echo json_encode(array('result' => false, 'message' => "Connection failed: " . mysqli_connect_error(), 'code' => mysqli_connect_errno()));
    exit();
}

echo json_encode(array('result' => true));
exit();
