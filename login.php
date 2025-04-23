<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        $result =$db->logIn("users", $_POST['username'], $_POST['password']);
        echo $result;
    } else {
        echo "Error: Database connection";
    }
} else {
    echo "All fields are required";
}
?>