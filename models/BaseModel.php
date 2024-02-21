<?php

abstract class BaseModel
{
    public static $db;
    protected $host = "fnx6frzmhxw45qcb.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
    protected $username = "tszgh948pnuwdmhm";
    protected $password = "wxic0rg5yyntykbr";
    protected $db_name = "an67nmpljgj86jxs";
    protected $port = 3306;

    function __construct()
    {
        $this->connectToDatabase();
    }
    protected function connectToDatabase()
    {
        // Establish database connection
        static::$db = mysqli_init();
        mysqli_ssl_set(static::$db, NULL, NULL, NULL, NULL, NULL);
        if (!mysqli_real_connect(static::$db, $this->host, $this->username, $this->password, $this->db_name, $this->port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }
        echo "Connected successfully to MySQL.";
        mysqli_set_charset(static::$db, "utf8");
    }
}
