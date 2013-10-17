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
        $sql = "SELECT `item_name`, `item_id`, `item_price`, `item_stock` FROM `{$db->name}`.`dbms_item` WHERE `item_sub_category` = '{$subCatId}'";
        $query = $db->query($sql);
        $opts = '';
        while($row = $db->result($query))   {
            if ( $row->item_stock <= 0) {
                continue;
            }
            $opts .= '<div class="itemHolder">';
            $opts .= '  <div class="itemImageHolder"> <img src="resources/images/'.$row->item_image_name.'" /> </div>';
            $opts .= '  <p class="itemNameHolder"> '.$row->item_name.'</p>';
            $opts .= '  <div class="itemNamePriceHolder">';
            $opts .= '      <div class="itemNameLeft"><p class="itemPriceHolder"> '.$row->item_price.'</p></div>';
            $opts .= '      <div class="itemPriceRight"><p class="itemNameHolder"> '.$row->item_name.'</p></div>';
            $opts .= '  </div>';
            $opts .= '</div>';
        }
    }
?>
