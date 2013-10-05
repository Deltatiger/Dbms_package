<?php

/*
 * Page that is used to change the registered User from Regular User to a Seller.
 */
    include 'includes/config.php';
    if ( !$session->isLoggedIn() || $session->isSeller())   {
        //We need a logged in user to register him as a seller.
        header('Location:index.php');
    }
    //We have to check the user already has a pending / confirmed status of a seller.
    $userId = $session->getUserId();
    $sql = "SELECT `seller_approved` FROM `{$db->name()}`.`dbms_seller_info` WHERE `seller_user_id` = '{$userId}'";
    $query = $db->query($sql);
    if (mysql_num_rows($query) > 0) {
        //We alreadty have a request from the user. We just display the status.
        $result = $db->result($query);
        $message = 'Your Seller Status is : '. ($result->seller_approved == '0' ? 'Pending' : 'Approved').'.' ;
        mysql_free_result($query);
    } else {
        if (isset($_POST['sRegister']))  {
            // This means that we have to get the stuff from DB.
            $sql = "INSERT INTO `{$db->name()}`.`dbms_seller_info` (`seller_user_id`) VALUES ('{$userId}')";
            $query = $db->query($sql);
            $message = 'Your request is under consideration. Please visit Later.';
        } else {
            $message = "<br><br><div><input type=\"submit\" value=\"Register as a Seller\" name=\"sRegister\" /></div>";
        }
    }
    $template->setTemplateVar('message', $message);
    $template->setPageTitle('Registration Page.');
    $template->setPage('sellerRegistration');
    $template->loadPage();
?>
