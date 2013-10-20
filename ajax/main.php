<?php
    /*
     * This whole page is solely dedicated for AJAX purposes.
     */

    include '../includes/config.php';
    
    if ( isset($_POST['catId']))    {
        /*
         *  Page : addItem.php
         *  Sub Category : Setting the sub category from the given category id.
         */
        $catId = mysql_real_escape_string($_POST['catId']);
        if ( $catId == '0') {
            echo '<option value="0"> Select a Category </option>';
        } else {
            $sql = "SELECT `sc_name`, `sc_id` FROM `{$db->name()}`.`dbms_sub_category` WHERE `sc_belongs_to` = '{$catId}'";
            $query = $db->query($sql);
            $options = '<option value="0"> Select a Sub Category </option>';
            while ($row = $db->result($query) )  {
                $options .= "<option value=\"{$row->sc_id}\"> {$row->sc_name} </option>";
            }
            echo $options;
        }
    } elseif( isset($_POST['iName']))   {
        $itemName = strtolower(trim(mysql_real_escape_string($_POST['iName'])));
        $sql = "SELECT COUNT(*) as rowCount FROM `{$db->name()}`.`dbms_item` WHERE LOWER(`item_name`) = '{$itemName}'";
        $query = $db->query($sql);
        $message = "";
        if ( $db->numRows($query) > 0)  {
            // We have names from the same name. We display the false result.
            if ( $db->result($query)->rowCount > 0) {
                $message = '<p class="errorMessageMedium">An item with the same name already Exists.</p>';
            }
        }
        echo $message;
    } elseif(isset($_POST['catIdShow']))    {
        /*
         * Page : index.php
         * Sub Category : Loading the list of sub categories for the required category.
         */
       $catId = $_POST['catIdShow'];
       $sql = "SELECT `sc_name`, `sc_id` FROM `{$db->name()}`.`dbms_sub_category` WHERE `sc_belongs_to` = '{$catId}'";
       $query = $db->query($sql);
       $out = '<ul id="catOptions">';
       while($row = $db->result($query))    {
           $out .= '<li> <a href="#" class="subCatName" data-subcatid="'.$row->sc_id.'">'.$row->sc_name.'</a></li>';
       }
       //This is to load back the Categories.
       $out .= '<li> <a href="#" id="showCat" > Back To Category </a></li>';
       $out .= '</ul>';
       echo $out;
    } elseif (isset($_POST['showCat'])) {
        /*
         * Page : index.php
         * Sub Category : Reload all the category options into the mLeftIndexContent
         */
        $sql = "SELECT `c_name`, `c_id` FROM `{$db->name()}`.`dbms_category`";
        $query = $db->query($sql);
        $catOptions = '<ul id="catOptions">';
        while($row = $db->result($query))   {
            $catOptions .= '<li> <a href="#" class="catName" data-catid="'.$row->c_id.' data-catname="'.$row->c_name.'">'.$row->c_name.'</a></li>';
        }
        $catOptions .= '<ul>';
        echo $catOptions;
    } elseif (isset($_POST['subCatId']))    {
        /*
         * Page : index.php
         * Sub Category : Adding all items of the given Subcategory into the mRightIndexContent
         */
        $subCatId = $_POST['subCatId'];
        $sql = "SELECT `item_name`, `item_id`, `item_price`, `item_stock` , `image_name`, `image_type` FROM `{$db->name()}`.`dbms_item`, `{$db->name()}`.`dbms_image`  WHERE `item_sub_category` = '{$subCatId}' AND `dbms_item`.`item_image_id` = `dbms_image`.`image_id`";
        $query = $db->query($sql);
        $opts = '';
        while($row = $db->result($query))   {
            if ( $row->item_stock <= 0) {
                continue;
            }
            $opts .= '<div class="itemHolder">';
            $opts .= '  <div class="itemImageHolder"> <img src="resources/images/'.$row->image_name.'.'.$row->image_type.'" height=150 width=150/> </div>';
            $opts .= '  <p class="itemNameHolder"> <a href="showItem.php?id='.$row->item_id.'">'.$row->item_name.'</a></p>';
            $opts .= '  <div class="itemPriceQtyHolder">';
            $opts .= '      <div class="itemPriceLeft"><p class="itemPriceHolder"> &#8377; '.$row->item_price.'</p></div>';
            $opts .= '      <div class="itemQtyRight"><p class="itemQtyHolder"> Stock :'.$row->item_stock.'</p></div>';
            $opts .= '  </div>';
            $opts .= '</div>';
        }
        echo $opts;
    } elseif(isset($_POST['showSellerStats']))  {
        /*
         * Page : sellerHome.php
         * Sub Category : Load the seller details in a neat table into the right pane.
         */
        $sellerId = $session->getUserId();
        $sql = "SELECT `item_name`, `item_stock`, `item_price`, `item_avg_rating` FROM `{$db->name()}`.`dbms_item` WHERE `item_seller_id` = '{$sellerId}'";
        $query = $db->query($sql);
        $table = '<div id="sellerStatHolder">';
        $table .= '<table class="sellerStatTable">';
        $table .= '<tr><th> Item Name </th><th> Item Stock </th> <th> Item Price </th> <th> Item Rating </th></tr>';
        while($row = $db->result($query))   {
            $table .= "<tr><td> {$row->item_name} </td><td> {$row->item_stock} </td><td> {$row->item_price} </td><td> {$row->item_avg_rating} </td></tr>";
        }
        $table .= '</table></div>';
        echo $table;
    } elseif(isset($_POST['itemId']) && isset($_POST['itemQty']))   {
        /*
         * Page : showItem.php
         * Sub Category : Add the given item into the basket.
         */
        $itemId = trim($_POST['itemId']);
        $itemQty = trim($_POST['itemQty']);
        if (isset($_POST['forceInsert']))   {
            $forceInsert = true;
        } else {
            $forceInsert = false;
        }
        $inBasket = $session->uBasket->isInBasket($itemId);
        //Now check if already in or not and do the required action.
        if ( $inBasket && $forceInsert == false) {
            //This asks for a confirmation dialogue.
            echo -1;
        } elseif ($inBasket && $forceInsert == true){
            //This has a force input hence procced to insert.
            $session->uBasket->updateItemQuantity($itemId, $itemQty);
            echo 1;
        } else {
            //First time insertion.
            $session->uBasket->addItem($itemId, $itemQty);
            echo 1;
        }
    } elseif(isset($_POST['refreshBasketCount']))   {
        /*
         * Page : showItem.php
         * Sub Category : This updates the basket count.
         */
        $data = 'My Basket ['.$session->uBasket->getBasketCount().']';
        echo $data;
    } elseif(isset($_POST['basketBuyid']))  {
        /*
         * Page : myBasket.php
         * Sub Category : This Pays for the current basket and changes the
         */
        if (!$session->isLoggedIn())    {
            //Cannot purchase until logged in.
            echo -1;
        } else {
            /*
             * 0. Check if all items are available.
             * 1. Change the basket status to pending.
             * 2. Create a new Basket for the logged in user.
             */
            $currentBasketId = $session->uBasket->getBasketId();
            //Step 0.
            $sql = "SELECT `item_id`
                        FROM `{$db->name()}`.`dbms_basket_contains`,
                            `{$db->name()}`.`dbms_item`
                        WHERE `dbms_basket_contains`.`basket_item_id` = `dbms_item`.`item_id` AND
                            `dbms_basket_contains`.`basket_id` = '{$currentBasketId}' AND
                            `dbms_basket_contains`.`basket_item_qty` > `dbms_item`.`item_stock`";
            $query = $db->query($sql);
            if ($db->numRows($query) > 0)    {
                //Some items are not having required quantity.
                //We remove them from the Basket and report to the user.
                while ($row = $db->result($query))  {
                    $session->uBasket->removeItem($row->item_id);
                }
                $db->freeResults($query);
                echo -2;
            } else {
                //Step 1.
                $sql = "UPDATE `{$db->name()}`.`dbms_basket` SET `basket_clear` = '-1' WHERE `basket_id` = '{$currentBasketId}'";
                $query = $db->query($sql);

                //Step 2.
                $session->uBasket = new Basket(-1);
                $session->uBasket->setBasketUser();
                echo $session->uBasket->getBasketId();
                echo 1;
            }
        }
    }
?>
