<?php
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
    }

    public function createConnection()
    {
        $connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($connection->connect_error) {
            echo "Connection failed: " . $connection->connect_error;
            exit;
        }

        return $connection;
    }
}
