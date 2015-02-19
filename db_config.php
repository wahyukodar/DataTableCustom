<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "nodejs");


class DB_Connect {
 
    // constructor
    function __construct() {
		//$this->connect();
	}
 
    // destructor
    function __destruct() {
		// $this->close();
	}
 
    // Connecting to database
    public function connect() {
        // connecting to mysql
        $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        // selecting database
        mysql_select_db(DB_DATABASE);
        // return database handler
        return $con;
    }
 
    // Closing database connection
    public function close() {
        mysql_close();
    }
 
} 
?>