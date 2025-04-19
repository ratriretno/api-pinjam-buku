<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['username'])) {
    if ($db->dbConnect()) {
        $profile = $db->getProfile("users", $_POST['username']);
        $obj_profile = json_decode (json_encode ($profile), FALSE);
        echo $obj_profile;
    } else echo "Error: Database connection";
} else echo "username fields are required";
?>