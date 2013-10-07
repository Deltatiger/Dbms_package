<?php
    /*
     * This page is used to add an item to the DB. 
     * Requirements : The User should be logged in and he should be approved Seller.
     */
     include_once 'includes/config.php';
     if ( !$session->isLoggedIn() || !$session->isSeller()) {
         // This means that the user is non valid user.
         header('Location:index.php');
     }
     $sql = "SELECT `c_name`, `c_id` FROM `{$db->name()}`.`dbms_category` ORDER BY `c_name`";
     $query = $db->query($sql);
     $options = '<option value="0"> Select a Category </option>';
     while($row = $db->result($query))  {
         $options .= "<option value=\"{$row->c_id}\"> {$row->c_name} </option>";
     }
     $template->setTemplateVar('catOptions', $options);
     $template->setPage('sadditem');
     $template->setPageTitle('Seller - Item Addition Page.');
     $template->loadPage();
?>
