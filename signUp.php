<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        if ($db->signUp("users", $_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'])) {
            echo "Sign Up Success";
        } else echo "Sign up Failed";
    } else echo "Error: Database connection";
} else if !isset($_POST['fullname']) || !isset($_POST['email']) || isset($_POST['username'])|| !isset($_POST['password']) {
    echo "empty $_POST['fullname'] $_POST['email'] $_POST['username'] $_POST['password'] "
} else echo "All fields are required";
?>