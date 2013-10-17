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
     if (isset($_POST['addItemSubmit']))   {
         $itemName = mysql_real_escape_string(trim($_POST['iName']));
         $itemCat = $_POST['iCat'];
         $itemSubCat = $_POST['iSubCat'];
         $itemPrice = mysql_real_escape_string(trim($_POST['iPrice']));
         $itemQty = mysql_real_escape_string(trim($_POST['iQty']));
         $imgId = 0;
         // This is the error message string.
         $errorMessage = '<ul class="errorMsg">';
         //We accept the Display pic after all validations are complete.
         $sql = "SELECT COUNT(`item_name`) as rowCount FROM `{$db->name()}`.`dbms_item` WHERE LOWER(`item_name`) = '".strtolower($itemName)."'";
         $query = $db->query($sql);
         $result = $db->result($query);
         if ($result->rowCount > 0)  {
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
         if(strlen($errorMessage) == 21)  {
             //We can upload the file now.
             if ( $_FILES['iDisplayImage']['error'] > 0)    {
                 // this means that there are some errors in the file upload process.
                 $errorMessage .= '<li>Item quantity should be a Number and Positive.</li>';
             } else {
                 if ( ($_FILES['iDisplayImage']['size'] > 0 && $_FILES['iDisplayImage']['size'] < 100000) && ($_FILES['iDisplayImage']['type'] == 'image/jpeg' || $_FILES['iDisplayImage']['type'] == 'image/gif' || $_FILES['iDisplayImage']['type'] == 'image/png' || $_FILES['iDisplayImage']['type'] == 'image/jpeg')) {
                     // The file size is within range. We move it once we confirm it 
                     if ( is_uploaded_file($_FILES['iDisplayImage']['tmp_name']))   {
                         //Bingo. Everything checks out fine.
                         $ROOTPATH = $_SERVER['DOCUMENT_ROOT'].'/dbms/Dbms_package/resources/images/';
                         $sql = "SELECT MAX(`image_id`) as lastNum FROM `{$db->name()}`.`dbms_image`";
                         $query = $db->query($sql);
                         $imgId = $db->result($query);
                         $imgId = $imgId->lastNum + 1;
                         if (move_uploaded_file($_FILES['iDisplayImage']['tmp_name'], 'resources/images/'.$_FILES['iDisplayImage']['name']))    {
                             //We move all the required data into the respective tables.
                             $fileName = $_FILES['iDisplayImage']['name'];
                             $fileExt = substr($fileName, strpos($fileName, '.') + 1);
                             $fileName = substr($fileName, 0, strpos($fileName, '.'));
                             $sql = "INSERT INTO `{$db->name()}`.`dbms_image`(`image_id`,`image_name`,`image_type`) VALUES ({$imgId}, '{$fileName}', '{$fileExt}')";
                             $query = $db->query($sql);
                         } else {
                             $errorMessage .= '<li>Image Upload Error. Try again or Contact Admin.</li>';
                         }
                     } else {
                         $errorMessage .= '<li>Image File Error. Try again or Contact Admin.</li>';
                     }
                 } else {
                     $errorMessage .= '<li>Image Should be less than 45KB and of types jpg, jpeg, gif, png.</li>';
                 }
             }
         }
         $errorMessage .= '</ul>';
         if (strlen($errorMessage) == 26)    {
             //We can proceed to add the item to the DB.
             $sellerId = $session->getUserId();
             $sql = "INSERT INTO `{$db->name()}`.`dbms_item` VALUES ('{$itemName}', NULL, {$itemSubCat}, {$sellerId}, {$itemPrice}, {$itemQty}, 0, {$imgId})";
             $query = $db->query($sql);
             $template->setTemplateVar('errorMessage', '<script type="text/javascript"> alert(\'Item Successfully Added.\'); window.location = "index.php"; </script> ');
         } else {
             $template->setTemplateVar('errorMessage', $errorMessage);
         }
     }
     $template->setPage('sadditem');
     $template->setPageTitle('Seller - Item Addition Page.');
     $template->loadPage();
?>
