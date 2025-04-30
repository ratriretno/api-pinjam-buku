<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['idUser']) && isset($_POST['idBuku'])) {
    if ($db->dbConnect()) {
        // if ($db->insertTransaction("transaction", $_POST['idUser'], $_POST['idBuku'])) {
        //     echo "insert transaction sucess";
        // } else echo "Failed";
        $res = $db->insertTransaction("transaction", $_POST['idUser'], $_POST['idBuku']);
        echo $res;
    } else echo "Error: Database connection";
} else if (isset($_POST['idUser']) && empty($_POST['idBuku']) ) {
    echo "idbuku kosong";
} else if (empty($_POST['idUser']) && isset($_POST['idBuku']) ) {
echo "idUser fields are required";
} else 
    echo "All fields are required";


?>