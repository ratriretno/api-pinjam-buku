<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['idTransaksi']) && isset($_POST['idBuku'])) {
    if ($db->dbConnect()) {
        $res= $db->returnBook("transaction", $_POST['idTransaksi'], $_POST['idBuku']) ;
        $result = "{
            \"error\": false,
            \"message\": \"".$res."\"}";

        echo $result;
    } else echo "Error: Database connection";
} else if (isset($_POST['idTransaksi']) && empty($_POST['idBuku']) ) {
    echo "idbuku kosong";
} else if (empty($_POST['idTransaksi']) && isset($_POST['idBuku']) ) {
echo "idTransaksi fields are required";
} else 
    echo "All fields are required";


?>