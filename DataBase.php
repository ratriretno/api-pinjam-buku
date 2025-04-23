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

        $result = "{
            \"error\": false,
            \"login\":".$login.",
            \"message\": \"Books fetched successfully\",
            \"id\":\"".$dbId."\"}";

        return $result;
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