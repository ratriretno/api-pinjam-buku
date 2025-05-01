<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['idUser'])) {
    if ($db->dbConnect()) {
        $message="sucess";
        $profile = $db->getProfile("users", $_POST['idUser']);
        $result = "{
            \"error\": false,
            \"login\": true,
            \"message\": \"".$message."\"",
            \"profile\":".json_encode($profile)."}";

        echo $result;
    } else echo "Error: Database connection";
} else echo "username fields are required";
?>