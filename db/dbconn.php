<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'peter');
    define('DB_PASSWORD', '1234');
    define('DB_DATABASE', 'webshop');

class DatabaseConnection
{
    public function __construct()
    {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if (!$conn) {
            die("<h1>Database Connection Failed</h1>" . mysqli_connect_error());
        }
        // echo "Database Connected Successfully";
        return $this->conn = $conn;
    }
}

?>