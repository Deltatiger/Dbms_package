<?php

/*
 * This is the file that works with the 
 */

class Basket  {
    /*
     * This class is the main thing that is used to handle all the transactions related to the Basket.
     * Includes everything from adding, removing, displaying and changing a basket.
     */
    
    // We only need a basket id to perform all the actions so we only have that.
    private $basketId;
    
    public function __construct($basketId)   {
        //This function is the heart and soul of the class. This uses the session to find out if the user needs a new Basket or not.
       if ( $basketId == -1)    {
           //This means we need a new Basket.
           $basketId = $this->createNewBasket();    //Creates and returns a new Basket Number.
       }
       //We assign the 
       $this->basketId = $basketId;
    }
    
    public function addItem($itemId)   {
        //This function is used to add an item to the Basket.
        //We first have to check if the item is already in the Basket.
        $sql = "SELECT COUNT(*) FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$this->basketId}' AND `basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
        if (mysql_num_rows($query) > 0)     {
            return False;
        } else {
            $sql = "INSERT INTO `{$db->name()}`.`dbms_basket_contains` VALUES ({$this->basketId}, {$itemId})";
            $query = $db->query($sql);
            return True;
        }
    }
    
    public function removeItem($itemId) {
        //This function is used to remove an item to the Basket.
        //We can just remove without checking if the item is actually in the DB or not.
        $sql = "DELETE FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$this->basketId}' AND `basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
    }
    
    public function getBasketId()   {
        return $this->basketId;
    }

    private function createNewBasket()  {
        //This function is used to create a new basket for the user.
        //STAGE - 1. Only create a new Basket.
        global $db;
        //First we have to get the topmost baskets rating.
        $sql = "SELECT MAX(`basket_id`) as maxBasketId FROM `{$db->name()}`.`dbms_basket`";
        $query = $db->query($sql);
        if (mysql_num_rows($query) < 0)    {
            $newBasketId = 1;
        } else {
            $result = mysql_fetch_object($query);
            $newBasketId = $result->maxBasketId + 1;
        }
        $sql = "INSERT INTO `{$db->name()}`.`dbms_basket` VALUES ({$newBasketId}, NULL, '0')";
        $query = $db->query($sql);
        return $newBasketId;
    }
    
    public function setBasketUser() {
        //This function is used to convert the current Basket from a non login to a login.
        //Some steps are that we have to check if there already exists a user with the same user_id.
        // If we do have a basket then we just change the basket contents if any into the main one and delete this one.
        global $session, $db;
        if (!$session->isLoggedIn())    {
            return False;
        }
        //We have to check for a basket with the same user id
        $userId = $session->getUserId();
        // Now we have to check if there are items in his current basket.
        $sql = "SELECT `session_basket_id` FROM `{$db->name()}`.`dbms_session` WHERE `session_id` = '{$_SESSION['session_id']}'";
        $query = $db->query($sql);
        $result = mysql_fetch_object($query);
        $newBasketId = $result->session_basket_id;
        $sql = "SELECT COUNT(*) as basket_count FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$newBasketId}'";
        $query = $db->query($sql);
        if(mysql_num_rows($query) >= 0)  {
            //We have some kind of result. We check out the details.
            $result = mysql_fetch_object($query);
            if ( $result->basket_count > 0) {
                //We have some items in the current basket. Now we have to check if the user has a basket.
                $sql = "SELECT `basket_id` FROM `{$db->name()}`.`dbms_basket` WHERE `basket_user_id` = '{$userId}'";
                $query = $db->query($sql);
                if (mysql_num_rows($query) > 0) {
                    //We have a basket for the user.
                    $result2 = mysql_fetch_object($query);
                    $existingBasketId = $result2->basket_id;
                    //Now we have to merge the items from the users new basket to the existing basket and then delete the new basket( prelogin basket ).
                    $sql = "UPDATE `{$db->name()}`.`dbms_basket_contains` SET `basket_id` = '{$existingBasketId}' WHERE `basket_id` = '{$newBasketId}'";
                    $query = $db->query($sql);
                    //Now we update the session info.
                    $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_basket_id` = '{$existingBasketId}' WHERE `session_id` = '{$_SESSION['session_id']}'";
                    $query = $db->query($sql);
                    //Now we dont need the old basket anymore. Just delete it.
                    $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$newBasketId}'";
                    $query = $db->query($sql);
                } else {
                    //We dont have an existing basket. So we convert this one into the user's one.
                    $sql = "UPDATE `{$db->name()}`.`dbms_basket` SET `basket_user_id` = '{$userId}' WHERE `basket_id` = '{$newBasketId}'";
                    $query = $db->query($sql);
                }
            } else {
                // We dont have any item in the basket. We check if there is basket for the user else we make this the users basket. If we already have a basket we delete this one.
                $sql = "SELECT `basket_id` FROM `{$db->name()}`.`dbms_basket` WHERE `basket_user_id` = '{$userId}'";
                $query = $db->query($sql);
                if ( mysql_num_rows($query) > 0)    {
                    //This means we already have a basket. We change the session details.
                    $result = mysql_fetch_object($query);
                    $existingBasketId = $result->basket_id;
                    //Now we update the session info.
                    $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_basket_id` = '{$existingBasketId}' WHERE `session_id` = '{$_SESSION['session_id']}'";
                    $query = $db->query($sql);
                    //Then we delete the old basket.
                    $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$newBasketId}'";
                    $query = $db->query($sql);
                } else {
                    //We dont have an existing basket. So we convert this one into the user's one.
                    $sql = "UPDATE `{$db->name()}`.`dbms_basket` SET `basket_user_id` = '{$userId}' WHERE `basket_id` = '{$newBasketId}'";
                    $query = $db->query($sql);
                }
                
            }
            return true;
        }
        return false;
    }
        
}
?>
