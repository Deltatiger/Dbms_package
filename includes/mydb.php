<?php

/*
 * This has the connection to the mysql database.
 */
class DB    {
    private $db;
    private $dbName;
  
    //This is the constructor
    function __construct() {
        $this->db = mysql_connect('localhost','root','');
        if (mysql_errno())  {
            die('Could not Connect to the DataBase.');
        }
               
        mysql_select_db("dbms_package", $this->db);
        
        $this->dbName = 'dbms_package';
    }
    
    public function name() {
        return $this->dbName;
    }
    
    public function num_Rows()  {
        //return mysql_num_rows($this->db);
    }
    
    public function query($sql) {
        $query = mysql_query($sql, $this->db) or die('SQL Error : '.  mysql_error().'<br />'.$sql);
        return $query;
    }
    
}
?>
