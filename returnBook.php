<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['idTransaksi']) && isset($_POST['idBuku'])) {
    if ($db->dbConnect()) {
        // if ($db->returnBook("transaction", $_POST['idTransaksi'], $_POST['idBuku'])) {
        //     echo "update transaction sucess";
        // } else echo "Failed";
        $res = $db->returnBook("transaction", $_POST['idTransaksi'], $_POST['idBuku'])
        echo $res
        
    } else echo "Error: Database connection";
} else if (isset($_POST['idTransaksi']) && empty($_POST['idBuku']) ) {
    echo "idbuku kosong";
} else if (empty($_POST['idTransaksi']) && isset($_POST['idBuku']) ) {
echo "idTransaksi fields are required";
} else 
    echo "All fields are required";


?>