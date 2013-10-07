<div id ="mBody">
    <div>
        <label for="iName">Item Name : </label>
        <input type="text" name="iName" id="aItemName" />
        <div id ="aItemNameExists">
        </div>
    </div>
    <div>
        <label for="iCat">Item Category : </label>
        <select name="iCat" id="aItemCat">
            <?php
                $this->printVar('catOptions');
            ?>
        </select>
    </div>
    <div>
        <label for="iSubCat">Item Sub Category : </label>
        <select name="iSubCat" id="aItemSubCat">
        </select>
    </div>
    <div>
        <label for="iPrice">Item Price : </label>
        <input type="text" name="iPrice" />
    </div>
    <div>
        <label for="iQty">Item Quantity : </label>
        <input type="text" name="iQty" />
    </div>
</div>