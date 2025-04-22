<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
require "DataBase.php";
$db = new DataBase();
    if ($db->dbConnect()) {
        $books = $db->getBooks("books");
        $booksArray= "{
            \"error\": false,
            \"message\": \"Books fetched successfully\",
            \"listBooks\": [".json_encode($books)."]}";
        echo $booksArray;
    } else echo "Error: Database connection";

?>