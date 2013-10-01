<?php

/**
 * Description of session
 *  This class is used to main the session of the user. This has the constructor which enables the init checking
 *
 * @author DeltaTiger
 */


class Session {
    private $uBasket;
    
    public function __construct() {
        //This is the main constructor method.
        $currentTime = time();
        $currentIp = $_SERVER['REMOTE_ADDR'];
        $currentBrowser = $_SERVER['HTTP_USER_AGENT'];
        
        //We also call the global $db object.
        global  $db;
        
        if ( isset($_SESSION['session_id']))    {
            //The user has an active session. We have to validate the session.
            $sessionId = $_SESSION['session_id'];
            $sql = "SELECT `session_last_active`, `session_create_ip`, `session_browser` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$sessionId}'";
            $query = $db->query($sql);
            if ( mysql_num_rows($query) == 0)    {
                //This means that the session does not exist. Unset it an make a new session.
                $this->createNewSession();
            } else {
                //He has a valid session in the Db. Check if it is still within 5 min activity and let him pass.
                $result = mysql_fetch_object($query);
                if ( $currentTime - intval($result->session_last_active) > 300 /* 5 Mins */)    {
                    //This means that the current Session is over due. Remove it and recreate it.
                    $this->createNewSession();
                } else {
                    //We check the final stage ie) the ip and browser.
                    if ( $result->session_create_ip != $currentIp || $result->session_browser != $currentBrowser)   {
                        //The session and IP dont match. Create a new Session.
                        $this->createNewSession();
                    } else {
                        //This means that the session is valid. Update the time and let him pass.
                        $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_last_active` = '{$currentTime}' WHERE `session_id` = '{$sessionId}'";
                        if ( ! $db->query($sql) )   {
                            die('Session Update Failed. Contact Admin.');
                        }
                    }
                }
                mysql_free_result($query);
            }
        } else {
            //This means we create a new session ID.
            if ( isset($_COOKIE['cookie_id']))  {
                //We seem to have a cookie. Validate it.
            } else {
                //Neither a Cookie nor a session. We create a new session.
                $this->createNewSession();
            }
        }
    }
    
    public function isLoggedIn()    {
        if(isset($_SESSION['session_id']))  {
            //We have a valid session.
            global $db;
            //We get the login status and return it.
            $sessionId = $_SESSION['session_id'];
            $sql = "SELECT `session_login_stat` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$sessionId}'";
            $query = $db->query($sql);
            if (mysql_num_rows($query) > 0) {
                //Return the valid session.
                $result = mysql_fetch_object($query);
                return ($result->session_login_stat == '1');
            } else {
                return False;
            }
        }
        return False;
    }
    
    public function login($username, $password) {
        // This is used to check the login credentials.
        if ($this->isLoggedIn())    {
            return false;
        } else {
            //We log the user in.
            global $db;
            $usernameClean = strtolower($username);
            $passwordHash = sha1($password);
            $sql = "SELECT `user_id` FROM `{$db->name()}`.`dbms_user` WHERE LOWER(`user_name`) = '{$usernameClean}' AND `user_pass` = '{$passwordHash}'";
            $query = $db->query($sql);
            if (mysql_num_rows($query) > 0) {
                //We proceed to log the user in.
                $result = mysql_fetch_object($query);
                $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_login_stat` = '1' , `session_user_id` = '{$result->user_id}' WHERE `session_id` = '{$_SESSION['session_id']}'";
                $query = $db->query($sql);
            }
            return true;
        }
    }
    
    public function getUserId() {
        // This gets the user id from the session.
        global $db;
        if ( isset($_SESSION['session_id']))    {
            //We have a session. We return the user_id if he has a login status 1.
            $sql = "SELECT `session_user_id`, `session_login_stat` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$_SESSION['session_id']}'";
            $query = $db->query($sql);
            if ( mysql_num_rows($query) > 0)    {
                //Bingo
                $result = mysql_fetch_object($query);
                if ( $result->session_login_stat == '1')    {
                    return $result->session_user_id;
                } else {
                    return 0;
                }
            } else {
                //We dont have any rows. We unset the session.
                unset($_SESSION['session_id']);
                return 0;
            }
        } else {
            //We dont have a session. We return 0.
            return 0;
        }
    }
    
    private function createNewSession() {
        //This is used to make a new session.
        $newSesId = $this->createNewSessionId();
        $currentTime = time();
        $currentIp = $_SERVER['REMOTE_ADDR'];
        $currentBrowser = $_SERVER['HTTP_USER_AGENT'];
        $oldBasketId = 0;
        global $db;
        if (isset($_COOKIE['cookie_id']))   {
            //We have a cookie. We need to add the table first.
            //TODO : Setup the cookie table and do the required.
        } else {
            if ( isset($_SESSION['session_id']))    {
                //We have to delete this from the Db and make a new one.
                //First we check if the user had any items in his Basket.
                $sql = "SELECT COUNT(`basket_item_id`) as itemsInBasket 
                    FROM `{$db->name()}`.`dbms_session`, `{$db->name()}`.`dbms_basket_contains` 
                    WHERE `dbms_session`.`session_basket_id` = `dbms_basket_contains`.`basket_id` AND
                        `dbms_session`.`session_id` = '{$_SESSION['session_id']}'";
                $query = $db->query($sql);
                if (mysql_num_rows($query) > 0) {
                    //This means that the basket has some items. We have to preserve the Basket Id.
                    $sql = "SELECT `session_basket_id` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$_SESSION['session_id']}'";
                    $query = $db->query($sql);
                    $result = mysql_fetch_object($query);
                    $oldBasketId = $result->session_basket_id;
                    $this->uBasket = new Basket($oldBasketId);
                } else {
                    //We dont have enough items in the Basket. Better we delete it.
                    $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = (SELECT `session_basket_id` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$_SESSION['session_id']}')";
                    $query = $db->query($sql);
                    $this->uBasket = new Basket(-1);
                    $oldBasketId = $this->uBasket->getBasketId();
                }
                $sql = "DELETE FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$_SESSION['session_id']}'";
                $db->query($sql);
                unset($_SESSION['session_id']);
            } else {
                //No session seems to be available. We make a new Basket for the user and assign it to him.
                $this->uBasket = new Basket(-1);
                $oldBasketId = $this->uBasket->getBasketId();
            }
            $sql = "INSERT INTO `{$db->name()}`.`dbms_session` VALUES ('{$newSesId}', NULL, {$currentTime}, {$currentTime}, 0, '{$currentIp}', '{$currentBrowser}', 0, {$oldBasketId})";
            $query = $db->query($sql);
            if ( !$query )   {
                die('Session Creation Problem. Contact Admin.');
            }
            $_SESSION['session_id'] = $newSesId;
        }
    }
    
    public function getUserNameFromSession()    {
        if (!$this->isLoggedIn())   {
            return false;
        }
        //Now we link the tables
        global $db;
        $sessionId = $_SESSION['session_id'];
        $sql = "SELECT `user_name` FROM `{$db->name()}`.`dbms_user`, `{$db->name()}`.`dbms_session` WHERE `dbms_session`.`session_user_id` = `dbms_user`.`user_id` AND `session_id` = '{$sessionId}'";
        $query = $db->query($sql);
        if (mysql_num_rows($query) > 0)     {
            $result = mysql_fetch_object($query);
            $userName = $result->user_name;
            mysql_free_result($query);
            return $userName;
        } else {
            return False;
        }
    }
    
    private function createNewSessionId()   {
        //This function is used to create a new id.
        $stringToCrpyt = generateRandString(6);
        $encrpytedString = sha1($stringToCrpyt);

        global $db;

        $sql = "SELECT `session_id` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$encrpytedString}'";
        $query = $db->query($sql);

        while($db->num_Rows() > 0) {
            $stringToCrypt = generateRandString(6);
            $encrpytedString = sha1($stringToCrypt);
            $query = $db->query($sql);
        }
        return $encrpytedString;
    }
}

?>