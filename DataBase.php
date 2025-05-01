<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $email, $password)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['email'];
            $dbpassword = $row['password'];
            $dbId = $row['id'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                $login = true;
            } else $login = false;
        } else $login = false;

        if($login==true){
            $message = "Sukses Login";
            $loginStatus = "true";
        } else {
            $message = "Email atau Password Salah";
            $loginStatus = "false";
        }

        $result = "{
            \"error\": false,
            \"login\":".$loginStatus.",
            \"message\": \"".$message."\",
            \"id\":\"".$dbId."\"}";

        return $result;
    }


    function getProfile($table, $idUser)
    {
        $idUser = $this->prepareData($idUser);
        $this->sql = "select * from " . $table . " where id = '" . $idUser . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
    
        return $row;
    }

    function getBooks ($table)
    {
        $sql = "SELECT books.id, books.name, books.description, books.photo_url, books.year, books.writer, books.id_borrower, books.publisher, books.available, users.full_name, books.id_owner FROM `books`, users WHERE books.id_owner=users.id;";
        $this->sql = $sql;
        $result = mysqli_query($this->connect, $this->sql);
        // $row = mysqli_fetch_assoc($result);

        $books = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	$books[] = $row;
    	}

        return $books;
    }

    function searchBooks ($keyword)
    {
        $this->sql = "SELECT * FROM books, users WHERE books.name like '%".$keyword."%' OR books.writer LIKE '%".$keyword."%' AND books.id_owner=users.id";
        $result = mysqli_query($this->connect, $this->sql);
        // $row = mysqli_fetch_assoc($result);

        $books = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	$books[] = $row;
    	}

        return $books;
    }

    function getTransaction ($keyword)
    {
        $sql = "SELECT transaction.id  AS 'id_transaksi', transaction.id_book AS 'id', books.name, books.description, books.photo_url, books.id_borrower, books.year, books.writer, books.publisher, books.available, users.full_name, books.id_owner FROM transaction,`books`, users WHERE books.id_owner=users.id AND transaction.id_book=books.id AND transaction.id_borrower='".$keyword."'";
        $this->sql = $sql;
        $result = mysqli_query($this->connect, $this->sql);
        // $row = mysqli_fetch_assoc($result);

        $books = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        	$books[] = $row;
    	}

        return $books;
    }

    function insertTransaction ($table, $idUser, $idBuku, $name)
    {
        $idBuku = $this->prepareData($idBuku);
        $idUser = $this->prepareData($idUser);
        $name = $this->prepareData($name);


        $sqlInsert =  "INSERT INTO " . $table . " (id_borrower, id_book) VALUES ('" . $idUser . "','" . $idBuku . "')";

        $sqlUpdate =  "UPDATE books SET available = 'false', id_borrower='" . $idUser . "' WHERE id='".$idBuku."'";

    
        if (mysqli_query($this->connect, $sqlInsert)) {
            $res = "Records buku".$idBuku." added successfully.";
            if (mysqli_query($this->connect, $sqlUpdate)) {
                $res = $name." berhasil dipinjam.";
                return $res;
            } else return $res;
        } else return false;
    }

    function signUp($table, $fullname, $email, $username, $password)
    {
        $fullname = $this->prepareData($fullname);
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $email = $this->prepareData($email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (full_name, username, password, email) VALUES ('" . $fullname . "','" . $username . "','" . $password . "','" . $email . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            $signup= true;
            $dbId=mysqli_insert_id($this->connect);
            $message = "Sucess Signup";
            $status = "true";
        } else {
            $message = "Failed Signup";
            $status = "false";
        }

        $result = "{
            \"error\": false,
            \"login\":".$status.",
            \"message\": \"".$message."\",
            \"id\":\"".$dbId."\"}";

        return $result;
    }

    function returnBook ($table, $idTransaksi, $idBuku)
    {
        $idBuku = $this->prepareData($idBuku);
        $idTransaksi = $this->prepareData($idTransaksi);

        $sqlUpdateBooks =  "UPDATE books SET available = 'true', id_borrower=' ' WHERE id='".$idBuku."'";
        $sqlUpdateTransaction = "UPDATE " . $table ." SET end_date = NOW() WHERE id='".$idTransaksi."'";

        if (mysqli_query($this->connect, $sqlUpdateTransaction)) {
            if (mysqli_query($this->connect, $sqlUpdateBooks)) {
                $res = "Buku berhasil dikembalikan";
                return $res;
            } else return false;
        } else return false;
    }


}

?>