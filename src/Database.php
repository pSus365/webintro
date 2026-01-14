<?php

class Database
{
    private $username;
    private $password;
    private $host;
    private $database;

    public function __construct()
    {
        $this->username = Configure::$db['user'];
        $this->password = Configure::$db['password'];
        $this->host = Configure::$db['host'];
        $this->database = Configure::$db['database'];
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode" => "prefer"]
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
