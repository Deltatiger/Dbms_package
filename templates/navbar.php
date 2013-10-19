<!-- This is the Common Navbar that appears on all pages -->

<div id ="navBar">
    <ul class="leftUL">
        <li><a href="index.php"> Home </a></li>
        <?php
            global $session;
            if ($session->isLoggedIn()) {
                if ( !$session->isSeller())  {
                    echo '<li><a href="sellerRegistration.php"> Register As a Seller </a></li>';
                } elseif ($session->isSeller()) {
                    // This displays the Seller Details.
                    echo '<li><a href="sellerHome.php"> Seller Details </a></li>';
                }
            }
        ?>
    </ul>
    <ul class="rightUL">
        <?php
            echo '<li>'.$session->getUserName().'</li>';
            echo '<li><a href="mybasket.php" id="myBasketLink">My Basket ['.$session->uBasket->getBasketCount().']</a></li>';
            if ($session->isLoggedIn()) {
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a href="registration_page.php">Register</a></li>';
            }
        ?>
    </ul>
</div>
<!-- Start the wrapper Div -->
<div id ="container">
    <div id="containerCenter">