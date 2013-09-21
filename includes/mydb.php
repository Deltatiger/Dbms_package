<?php

/*
 * This has the connection to the mysql database.
 */
class DB    {
    private $db;
    private $dbName;
  
    //This is the constructor
    function __construct() {
        $this->db = mysqli_connect('localhost','root','');
        if ( mysqli_connect_errno())    {
            die('Could not connect to DB. Please contact Admin.');
        }
        if (mysqli_connect_error()) {
            die('Could not connect to DB. Please contact Admin.');
        }
        
        mysqli_select_db($this->db, "dbms_package");
        
        $this->dbName = "dbms_package";
    }
    
    public function getDbName() {
        return $this->dbName;
    }
    
    public function query($sql) {
        return mysqli_query($this->db, $sql) or die('SQL Error : '.  mysqli_error($this->db));
    }
    
    function __destruct() {
        $this->db->close();
    }
}
?>
