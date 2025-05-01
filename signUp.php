<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        $result = $db->signUp("users", $_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password']);
       echo $result;
    } else echo "Error: Database connection";
} else if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['username'])|| empty($_POST['password'])) {
    echo "empty";
} else echo "All fields are required";
?>