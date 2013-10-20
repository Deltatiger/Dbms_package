<?php

    include 'includes/config.php';
    
    /*
     * This page neatly displays all the items in the current basket.
     * It also displays all the previous Baskets if the user is logged in.
     */
    $basketId = $session->uBasket->getBasketId();
    $options = '<table id="mBasketTable">';
    
    $query = $db->query("CALL display_basket_items({$basketId})");
    if($db->numRows($query) <= 0)   {
        //No items in the basket.
        $options .= '<tr><td class="centerTableCell"> No items in the Basket </td></tr>';
    } else {
        while($row = $db->result($query))   {
            $options .= '<tr>';
            $options .= '<td class="mBasketTableImage"> <img src="resources/images/'.$row->image_name.'.'.$row->image_type.'" height=150px width=150px /> </td>';
            $options .= '<td class="mBasketTableName">'.$row->item_name.'</td>';
            $options .= '<td class="mBasketTableQty">'.$row->basket_item_qty.'</td>';
            $options .= '<td class="mBasketTablePrice"> &#8377; '.$row->item_price.'</td>';
            $options .= '<td class="mBasketTableTCost"> &#8377; '.((int)$row->basket_item_qty * (int)$row->item_price).'</td>';
            $options .= '</tr>';
        }
        //The Procedure call problem.
        $db->freeResults($query);
        $db->reconnect();
        $query = $db->query("CALL get_total_basket_price({$basketId})");
        $result = $db->result($query);
        if($result->total_basket_cost == 'NULL')    {
            $result = 0;
        } else {
            $result = $result->total_basket_cost;
        }
        $options .= '<tr><td class="mBasketTableImage"></td><td class="mBasketTableName"> </td> <td class="mBasketTableQty"></td><td class="mBasketTablePrice">Total Cost : </td><td class="mBasketTableTotal">&#8377; '.$result.'</td></tr>';
    }
    $options .= '</table>';
    
    //The Procedure call problem.
    $db->freeResults($query);
    $db->reconnect();
    $template->setTemplateVar('items', $options);
    
    //We get all the baskets of the user if he is logged in.
    $basketLinks = '';
    if($session->isLoggedIn())  {
        $sql = "SELECT `basket_id` FROM `{$db->name()}`.`dbms_basket` WHERE `basket_user_id` = '{$session->getUserId()}' AND `basket_clear` = '1' ORDER BY `basket_id` DESC";
        $query = $db->query($sql);
        $basketCount = 0;
        if($db->numRows($query) <= 0)   {
            $basketLisks .= '<li> No Previous Baskets Found </li>';
        } else {
            while($row = $db->result($query))   {
                $basketLinks .= '<li> <a href="#" class="mBasketOldLinks" data-basketid="'.$row->basket_id.'"> Basket '.$row->basket_id.'</a></li>';
            }
        }
        $db->freeResults($query);
    } else {
        $basketLinks .= '<li> Login to See previous Baskets </li>';
    }
    $template->setTemplateVar('pBaskets', $basketLinks);
    
    $template->setPage('basket');
    $template->setPageTitle('My Basket');
    $template->loadPage();
?>
