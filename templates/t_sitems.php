<div id="mBody">
    <div id="mShowItem">
        <div id="mShowItemNameHolder">
            <p class="mShowItemName"> <?php $this->printVar('itemname'); ?> </p>
        </div>
        
        <div id="mShowItemImageHolder">
            <img src="resources/images/<?php $this->printVar('itemimage'); ?>" </img>
        </div>
        <div id="mShowItemTableWrapper">
            <table class="itemStatsTable">
                <tr> <td> <b> Item Price </b> </td> <td> &#8377; <?php $this->printVar('itemprice'); ?> </td> </tr>
                <tr> <td> <b> Item Stock </b> </td> <td> <?php $this->printVar('itemstock'); ?> </td> </tr>
                <tr> <td> <b> Item Seller </b> </td> <td> <?php $this->printVar('itemseller'); ?> </td> </tr>
                <tr> <td> <b> Item Rating </b> </td> <td> <?php $this->printVar('itemrating'); ?> </td> </tr>
                <tr> <td> <input type="text" name="itemQty" id="itemQty" class="mInputStyle" value="1"/> </td> <td> <a href="#" id="addItemBasket"> Add to Basket </a></td> </tr>
            </table>
        </div>
        <div class="clearDiv">
        </div>
        <input type="hidden" name="itemId" id="itemId" value="<?php $this->printVar('itemid'); ?>" />
        <p class="leftBold" > Ratings : </p>

        <div id="mShowItemRatings">
            <?php $this->printVar('ratings') ?>
        </div>

        <div id="mShowItemUserRating">
            <?php if ($this->printVar('hasBought'))  {?>
                Enter your Rating : 
                <select name="rating" id="userRating">
                    <option value="0"> 0 </option>
                    <option value="1"> 1 </option>
                    <option value="2"> 2 </option>
                    <option value="3"> 3 </option>
                    <option value="4"> 4 </option>
                    <option value="5"> 5 </option>
                </select>
                <input type="submit" id="userRatingSubmit" />
            <?php } ?>
        </div>
    </div>
</div>