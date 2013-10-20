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
        //This function is the core of the class. This uses the session to find out if the user needs a new Basket or not.
       if ( $basketId == -1)    {
           //This means we need a new Basket.
           $basketId = $this->createNewBasket();    //Creates and returns a new Basket Number.
       }
       //We assign the basketId to the class variable.
       $this->basketId = $basketId;
    }
    
    public function addItem($itemId, $itemQty)   {
        //This function is used to add an item to the Basket.
        //We first have to check if the item is already in the Basket.
        global $db;
        $sql = "INSERT INTO `{$db->name()}`.`dbms_basket_contains` VALUES ({$this->basketId}, {$itemId}, {$itemQty}, 0)";
        $query = $db->query($sql);
        return True;
    }
    
    public function removeItem($itemId) {
        //This function is used to remove an item to the Basket.
        //We can just remove without checking if the item is actually in the DB or not.
        global $db;
        $sql = "DELETE FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$this->basketId}' AND `basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
    }
    
    public function updateItemQuantity($itemId, $newItemQty)    {
        //This function is used to update the item Quantity since primary key will not allow a new record insertion.
        global $db;
        $sql = "UPDATE `{$db->name()}`.`dbms_basket_contains` SET `basket_item_qty` = `basket_item_qty` + {$newItemQty} WHERE `basket_id` = '{$this->basketId}' AND `basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
        return True;
    }
    
    public function getBasketId()   {
        return $this->basketId;
    }
    
    public function getBasketCount()    {
        //This function is used to get the item count using the sessions basket.
        global $db;
        $sql = "SELECT COUNT(`basket_item_id`) as itemCount FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$this->basketId}'";
        $query = $db->query($sql);
        $result = $db->result($query);
        $db->freeResults($query);
        return $result->itemCount;
    }
    
    public function isInBasket($itemId) {
        //This function is used to check if the given `itemId` already exists in the current basket.
        global $db;
        $sql = "SELECT COUNT(`basket_item_id`) as itemCount FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$this->basketId}' AND `basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
        $result = $db->result($query);
        $db->freeResults($query);
        if ($result->itemCount > 0)     {
            return true;
        } else {
            return false;
        }
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
            $result = $db->result($query);
            $newBasketId = $result->maxBasketId + 1;
        }
        $db->freeResults($query);
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
        $basketId = $this->basketId;
        //First we have to check if the current userId has an already existing Basket.
        $sql = "SELECT `basket_id` FROM `{$db->name()}`.`dbms_basket` WHERE `basket_user_id` = '{$userId}' AND `basket_clear` = '0'";
        $query = $db->query($sql);
        if ( $db->numRows($query) >= 1) {
            //We seem to have a basket. Now to check if that basket has any items.
            $uHasBasket = $db->result($query);
            $existingBasketId = $uHasBasket->basket_id;
            
            $db->freeResults($query);
            $sql = "SELECT COUNT(`basket_item_id`) as basketItemCount FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$existingBasketId}'";
            $query = $db->query($sql);
            $result = $db->result($query);
            
            if($result->basketItemCount > 0)    {
                //There are some items in the basket.
                $db->freeResults($query);
                /*
                 * 1. Reinsert all items in the `existingBasketId` into the current Basket.
                 * 2. Delete the `existingBasket` 
                 * 3. Set current baskets user to `userId`.
                 */
                
                //Step 1.
                $sql = "SELECT `basket_item_id`, `basket_item_qty` FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$existingBasketId}'";
                $query = $db->query($sql);
                while ( $row = $db->result($query)) {
                    if ($this->isInBasket($row->basket_item_id))    {
                        $this->updateItemQuantity($row->basket_item_id, $row->basket_item_qty);
                    } else {
                        $this->addItem($row->basket_item_id, $row->basket_item_qty);
                    }
                }
                $db->freeResults($query);
                //Step 2.
                $sql = "DELETE FROM `{$db->name()}`.`dbms_basket_contains` WHERE `basket_id` = '{$existingBasketId}'";
                $query = $db->query($sql);
                
                $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$existingBasketId}'";
                $query = $db->query($sql);
                
            } else {
                //There exists a Basket with 0 items. Delete the Basket.
                $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$existingBasketId}'";
                $db->query($sql);
            }
            
            //Step 3.
            $sql = "UPDATE `{$db->name()}`.`dbms_basket` SET `basket_user_id` = '{$userId}' WHERE `basket_id` = '{$basketId}'";
            $query = $db->query($sql);            
        } else {
            //No basket with the same userId.
            /*
             * 1. Set the current basket's userId to userId.
             * 2. After buying the Basket is changed. So set the sessions basketId to the same.
             */
            //Step 1
            $sql = "UPDATE `{$db->name()}`.`dbms_basket` SET `basket_user_id` = '{$userId}' WHERE `basket_id` = '{$basketId}'";
            $query = $db->query($sql);
            
            //Step 2
            $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_basket_id` = '{$basketId}' WHERE `session_id` = '{$_SESSION['session_id']}'";
            $query = $db->query($sql);
        }
        

        
        //TODO remodel this part.
        /*
        if ( $result->basket_count > 0) {
            //We have some items in the current basket. Now we have to check if the user has a basket.
            $sql = "SELECT `basket_id` , COUNT(`basket_id`) as uHasBasket FROM `{$db->name()}`.`dbms_basket` WHERE `basket_user_id` = '{$userId}'";
            $query = $db->query($sql);
            $userBasket = $db->result($sql);
            if ($userBasket->uHasBasket > 0) {
                //We have a basket for the user.
                $existingBasketId = $userBasket->basket_id;
                //Now we have to merge the items from the users new basket to the existing basket and then delete the new basket( prelogin basket ).
                $sql = "UPDATE `{$db->name()}`.`dbms_basket_contains` SET `basket_id` = '{$existingBasketId}' WHERE `basket_id` = '{$basketId}'";
                $query = $db->query($sql);
                //Now we update the session info.
                $sql = "UPDATE `{$db->name()}`.`dbms_session` SET `session_basket_id` = '{$existingBasketId}' WHERE `session_id` = '{$_SESSION['session_id']}'";
                $query = $db->query($sql);
                //Now we dont need the old basket anymore. Just delete it.
                $sql = "DELETE FROM `{$db->name()}`.`dbms_basket` WHERE `basket_id` = '{$basketId}'";
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

        }*/
        return true;
    }
        
}
?>
