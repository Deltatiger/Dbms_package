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
    }
?>
