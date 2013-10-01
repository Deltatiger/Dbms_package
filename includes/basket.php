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
    }
    
    public function removeItem($itemId) {
        //This function is used to remove an item to the Basket.
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
}
?>
