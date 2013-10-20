<div id="mBody">
    <div id="mBasketWrapper">
        <div id="mBasketLeft">
            <!-- This shows all the Previous Baskets of the current User.-->
            <p class="centerBold">Options</p>
            <ul class="mBasketSelections">
                <li> <a href="#" class="mBasketOldLinks" id="currentBasket" data-basketid="<?php $this->printVar('currentBasketId'); ?>"> Current Basket </a> </li>
                <hr />
                <?php $this->printVar('pBaskets'); ?>
            </ul>
        </div>
        <div id="mBasketRight">
            <?php $this->printVar('items'); ?>
            <?php if ($this->printVar('canShowBasketPay')) { ?>
            <div id="mBasketPayHolder">
                <a href="#" id="mBasketPay" class=""> Pay For Basket </a>
            </div>
            <?php } ?>
        </div>
        <div class="clearDiv"></div>
    </div>
</div>