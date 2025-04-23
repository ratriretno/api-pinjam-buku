<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        $result =$db->logIn("users", $_POST['email'], $_POST['password']);
        echo $result;
    } else {
        echo "Error: Database connection";
    }
} else {
    echo "All fields are required";
}
?>