<div id ="mBody">
    <form action="addItem.php" enctype="multipart/form-data" method="post">
        <div>
            <label for="iName">Item Name : </label>
            <input type="text" name="iName" id="aItemName" placeholder=" Name of item " autofocus="on"/>
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
            <input type="text" name="iPrice" placeholder=" Price of the Item " />
        </div>
        <div>
            <label for="iQty">Item Quantity : </label>
            <input type="text" name="iQty" placeholder=" Quantity required " />
        </div>
        <div>
            <label for="iQty">Item Display Image : </label>
            <input type="file" name="iDisplayImage" />
        </div>
        <div>
            <input type="submit" name="addItemSubmit" id="aItemSubmit" value="Add Item" disabled="true"/>
        </div>
        <div align="center">
            <?php 
                $this->printVar('errorMessage');
            ?>
        </div>
    </form>
</div>