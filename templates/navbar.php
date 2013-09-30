<!-- This is the Common Navbar that appears on all pages -->

<div id ="navBar">
    <ul>
        <li> Home </li>
        <li> About Us</li>
        <?php
            global $session;
            if ($session->isLoggedIn()) {
                echo '<li><a href="logout.php"> Logout </a></li>';
            }
        ?>
    </ul>
</div>