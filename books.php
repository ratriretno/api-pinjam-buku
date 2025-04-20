<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
require "DataBase.php";
$db = new DataBase();
    if ($db->dbConnect()) {
        $books = $db->getBooks("books");
        echo json_encode($books);
    } else echo "Error: Database connection";

?>