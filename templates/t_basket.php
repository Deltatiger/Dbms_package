<div id="mBody">
    <div id="mBasketWrapper">
        <div id="mBasketLeft">
            <!-- This shows all the Previous Baskets of the current User.-->
            <p class="centerBold">Options</p>
            <ul class="mBasketSelections">
                <li> <a href="#" class="mBasketOldLinks" data-basketid="<?php $this->printVar('currentBasketId'); ?>"> Current Basket </a> </li>
                <hr />
                <?php $this->printVar('pBaskets'); ?>
            </ul>
        </div>
        <div id="mBasketRight">
            <?php $this->printVar('items'); ?>
            <div id="mBasketPayHolder">
                
            </div>
        </div>
        <div class="clearDiv"></div>
    </div>
</div>