<?php

    include 'includes/config.php';
    if(!isset($_GET['id']))    {
        header('Location:index.php');
    }
    
    //This page is used to add items to a basket.
    $itemId = $_GET['id'];
    $sql = "SELECT `item_name`, `item_id`, `user_name`, `item_price`, `item_stock`, `item_avg_rating` , `image_name`, `image_type` 
        FROM `{$db->name()}`.`dbms_item`,
            `{$db->name()}`.`dbms_user`,
            `{$db->name()}`.`dbms_image`
        WHERE `item_id` = '{$itemId}' AND
            `dbms_item`.`item_seller_id` = `dbms_user`.`user_id` AND
            `dbms_item`.`item_image_id` = `dbms_image`.`image_id`";
    $query = $db->query($sql);
    if($db->numRows($query) <= 0)   {
        header('Location:index.php');
    }
    $result = $db->result($query);
    
    //This sets most of the template variables.
    $template->setTemplateVars(array(
        'itemname'  => $result->item_name,
        'itemprice' => $result->item_price,
        'itemstock' => $result->item_stock,
        'itemseller'=> $result->user_name,
        'itemrating'=> $result->item_avg_rating,
        'itemimage' => $result->image_name.'.'.$result->image_type,
        'itemid'    => $result->item_id
    ));
    
    //Now have to get all the ratings for the current item.
    $sql = "SELECT `user_name` , `rating_value` , `rating_text` 
        FROM `{$db->name()}`.`dbms_ratings` , 
            `{$db->name()}`.`dbms_user`
        WHERE `rating_item_id` = '{$itemId}' AND
            `dbms_user`.`user_id` = `dbms_ratings`.`rating_user_id`";
    $query = $db->query($sql);
    $opts = '';
    if($db->numRows($query) > 0)    {
        while($row = $db->result($query))   {
            $opts .= '<div class="ratingHolder">';
            $opts .= '  <div class="ratingValUserHolder">';
            $opts .= '      <div class="ratingUserHolder"> <p class="ratingUserText">'.$row->user_name.' </p></div>';
            $opts .= '      <div class="ratingValHolder"> <p class="ratingValText"> <b>'.$row->rating_value.' </b> / 5 </p></div>';
            $opts .= '      <div class="clearDiv"> </div>';
            $opts .= '  </div>';
            $opts .= '  <p class="ratingText">'.$row->rating_text.'</p>';
            $opts .= '</div>';
        }
    } else {
        $opts .= '<p class="messageBlackBold"> No Ratings Entered. </p>';
    }
    $template->setTemplateVar('ratings', $opts);
    
    //Now we check if the user can give ratings. i.e ) he has to have bought the item before rating.
    if ( $session->isLoggedIn())    {
        $userId = $session->getUserId();
        $sql = "SELECT COUNT(`basket_item_id`) as hasBought
            FROM `{$db->name()}`.`dbms_basket`, 
                `{$db->name()}`.`dbms_basket_contains`
            WHERE `dbms_basket`.`basket_id` = `dbms_basket_contains`.`basket_id` AND
                    `dbms_basket`.`basket_user_id` = '{$userId}' AND
                    `dbms_basket_contains`.`basket_item_id` = '{$itemId}'";
        $query = $db->query($sql);
        $result = $db->result($query);
        if($result->hasBought > 0)  {
            $template->setTemplateVar('hasBought', true);
        } else {
            $template->setTemplateVar('hasBought', false);
        }
    } else {
        $template->setTemplateVar('hasBought', false);
    }
    
    $template->setPage('sitems');
    $template->setPageTitle('Item View.');
    $template->loadPage();
?>
