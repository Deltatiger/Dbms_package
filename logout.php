<?php
    /*
     *  This is the main logout page that is used to unset the session and cookie.
     */

     include_once 'includes/config.php';
     if ( !$session->isLoggedIn())   {
         //This means he has no rights to logout. We kick him out.
         header('Location:index.php');
     }
     //First we unset the session of the user.
     $sessionId = $_SESSION['session_id'];
     unset($_SESSION['session_id']);
     $sql = "DELETE FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$sessionId}'";
     $db->query($sql);
     
     if ( isset($_COOKIE['cookie_id'])) {
         //We have to remove the cookie from the DB too.
         $cookieId = $_COOKIE['cookie_id'];
         $sql = "DELETE FROM `{$db->name()}`.`dbms_cookie` WHERE `cookie_id` = '{$cookieId}'";
         $db->query($sql);
         unset($_COOKIE['cookie_id']);
     }
     //Now we redirect the user to the index page.
     header('Location:index.php');
?>
