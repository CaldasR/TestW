<?php

Class Db { 
    
    // The MySQL service named in the docker-compose.yml.
    private $host = 'db';

    // Database use name
    private $user = 'MYSQL_USER';

    // Database user password
    private $pass = 'MYSQL_PASSWORD';

    // Database name
    private $mydatabase = 'MYSQL_DATABASE';

    private $conn = null;

    function __construct() {
        if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
            die('We don\'t have mysqli!!!');
        }

        // check the MySQL connection status
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->mydatabase);
        // var_dump($conn->connect_error);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else {
            // echo "Connected to MySQL server successfully!<br>";
        }
    }

    public function getConnect() {
        return $this->conn;
    }

    public function escape($a) {
        return $this->conn->real_escape_string($a);
    }
}

$myDb = new Db;
