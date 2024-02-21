<?php

abstract class BaseModel
{

    // public static $db;

    // protected $host = "aws.connect.psdb.cloud";
    // protected $username = "z3kunppp1s7l2i0c89a2";
    // protected $password = "pscale_pw_52nT6ilKXB4U2ihgWg50omgcHcfBdsDo39vKJWZwe25";
    // protected $db_name = "volunteer";
    
    // protected $host = "localhost";
    // protected $username = "root";
    // protected $password = "";
    // protected $db_name = "volunteer_api";
    // function __construct()
    // {
    //     static::$db = mysqli_connect($host, $username, $password, $db_name);
    //     if (mysqli_connect_errno()) {
    //         echo "Failed to connect to MySQL: " . mysqli_connect_error();
    //     } else {
    //         echo "connect";
    //     }
    //     mysqli_set_charset(static::$db, "utf8");
    // }
    public static $db;
    protected $host = "sql6.freemysqlhosting.net";
    protected $username = "sql6685474";
    protected $password = "CAc3xKLBIk";
    protected $db_name = "sql6685474";
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
