<!-- This is the main Index page that will hold everything -->
<div id ="mBody">
    <!-- This page is divided into 2 parts -->
    <div id="mIndexLeft">
        <p id="leftIndexHeading"> Categories </p>
        <div id="mIndexLeftContent">
            <?php
                $this->printVar('category');
            ?>
        </div>
    </div>
    <div id="mIndexRight">
        <div id="mRightIndexContent">
            Click on a Category To Begin.
        </div>
    </div>
</div>