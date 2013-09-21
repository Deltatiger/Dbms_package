<?php

/**
 * Description of session
 *  This class is used to main the session of the user. This has the constructor which enables the init checking
 *
 * @author DeltaTiger
 */
class Session {
    public function __construct() {
        //This is the main constructor method.
        if ( isset($_SESSION['session_id']))    {
            //The user has an active session. We have to validate the session.
            
        } else {
            //This means we create a new session ID.
        }
    }
    
    private function createNewSessionId()   {
        //This function is used to create a new id.
        $stringToCrpyt = generateRandString(6);
        $encrpytedString = sha1($stringToCrpyt);

        global $db;

        $sql = "SELECT `session_id` FROM `{$db->get_Db_Name()}`.`dbms_session` WHERE `session_id` = '{$encrpytedString}'";
        $query = $db->query($sql);

        while(mysql_num_rows($query) > 0) {
            $stringToCrypt = generateRandString(6);
            $encrpytedString = sha1($stringToCrypt);
            $query = $db->query($sql);
        }
        return $encrpytedString;
    }
}

?>
