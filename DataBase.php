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
        // $username = $this->prepareData($username);
        // $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['email'];
            $dbpassword = $row['password'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                $login = true;
                $booksArray= "{
                    \"error\": false,
                    \"message\": \"Login Success\",
                    \"login\": true,
                    \"profile\": ".json_encode($row)."}";
            } else {
                $booksArray= "{
                    \"error\": false,
                    \"login\": false,
                    \"message\": \"Password atau Email Salah\",
                    \"profile\": "[]"}";
            }
        } else  $booksArray= "{
            \"error\": false,
            \"login\": false,
            \"message\": \"Password atau Email Salah\",
            \"profile\": "[]"}";;


        return $booksArray;
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
            return true;
        } else return false;
    }

    function getProfile($table, $username)
    {
        $username = $this->prepareData($username);
        $this->sql = "select * from " . $table . " where username = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
    
        return $row;
    }

    function getBooks ($table)
    {
        $this->sql = "select * from " . $table .", users WHERE ". $table .".id_owner=users.id";
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

}

?>