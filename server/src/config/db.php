<?php

class Db
{
    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;

    public function __construct()
    {
        $this->dbhost = $_ENV['MySQL_DB_HOST'];
        $this->dbuser = $_ENV['MySQL_DB_USER_NAME'];
        $this->dbpass = $_ENV['MySQL_DB_PASSWORD'];
        $this->dbname = $_ENV['MySQL_DB_NAME'];
    }


    public function connect()
    {
        $mysql_connection = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
        $connection = new PDO($mysql_connection, $this->dbuser, $this->dbpass);
        $connection->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}
