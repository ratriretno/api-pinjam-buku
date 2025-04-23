<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $books = $db->searchBooks($_GET['keyword']);
    $booksArray= "{
        \"error\": false,
        \"message\": \"Books fetched successfully\",
        \"listBooks\": ".json_encode($books)."}";
    echo $booksArray;
} else echo "Error: Database connection";   

?>