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
     /*
      * This part deals with getting the input and validating it.
      */
     if (isset ($_POST['addItemSubmit']))   {
         $itemName = mysql_real_escape_string(trim($_POST['iName']));
         $itemCat = $_POST['iCat'];
         $itemSubCat = $_POST['iSubCat'];
         $itemPrice = mysql_real_escape_string(trim($_POST['iPrice']));
         $itemQty = mysql_real_escape_string(trim($_POST['iQty']));
         // This is the error message string.
         $errorMessage = '';
         //We accept the Display pic after all validations are complete.
         $sql = "SELECT COUNT(*) FROM `{$db->name()}`.`dbms_item` WHERE LOWER(`item_name`) = '".strtolower($itemName)."'";
         $query = $db->query($sql);
         if ($db->numRows($query) > 0)  {
             // We have a item with the same name.
             $errorMessage .= '<li>An item with the same name already exists.</li>';
         }
         // Now for the Sub category check.
         $sql = "SELECT `sc_name` FROM `{$db->name()}`.`dbms_sub_category` WHERE `sc_id` = '{$itemSubCat}'";
         $query = $db->query($sql);
         if ( $db->numRows($query) <= 0)    {
             // No such sub category exists. Some kind of a hack.
             $errorMessage .= '<li>Please select a appropriate sub category.</li>';
         }
         // Now for the numeric checks.
         if (!is_numeric($itemPrice) && intval($itemPrice) > 0)    {
             $errorMessage .= '<li>Item Price should be a Number and Positive.</li>';
         }
         if (!is_numeric($itemQty)&& intval($itemQty) > 0) {
             $errorMessage .= '<li>Item quantity should be a Number and Positive.</li>';
         }
         //Now if all is right we can upload the image file.
         if(strlen($errorMessage) == 0)  {
             //We can upload the file now.
             if ( $_FILES['iDisplayImage']['error'] > 0)    {
                 // this means that there are some errors in the file upload process.
                 $errorMessage .= '<li>Item quantity should be a Number and Positive.</li>';
             } else {
                 if ( $_FILES['iDisplayImage']['size'] > 0 && $_FILES['iDisplayImage']['size'] < 50000 && ($_FILES['iDisplayImage']['type'] == 'jpeg' || $_FILES['iDisplayImage']['type'] == 'gif' || $_FILES['iDisplayImage']['type'] == 'bmp')) {
                     // The file size is within range. We move it once we confirm it 
                     if ( is_uploaded_file($_FILES['iDisplayImage']))   {
                         //Bingo. Everything checks out fine.
                         $ROOTPATH = $_SERVER['DOCUMENT_ROOT'].'/dbms/Dbms_package/resources/images/';
                         $sql = "SELECT MAX(`file_id`) FROM `{$db->name()}`.`dbms_file_info`";
                         
                         
                     }
                 }
             }
         }
     }
     $template->setPage('sadditem');
     $template->setPageTitle('Seller - Item Addition Page.');
     $template->loadPage();
?>
