<!-- This is the Common Navbar that appears on all pages -->

<div id ="navBar">
    <ul>
        <li> Home </li>
        <li> About Us</li>
        <?php
            global $session;
            if ($session->isLoggedIn()) {
                echo '<li><a href="logout.php"> Logout </a></li>';
                if ( !$session->isSeller())  {
                    echo '<li><a href="sellerRegistration.php"> Register As a Seller </a></li>';
                }
            } else {
                echo '<li><a href="login.php"> Login </a></li>';
                echo '<li><a href="registration_page.php"> Register </a></li>';
            }
        ?>
    </ul>
</div>