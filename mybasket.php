<?php

    include 'includes/config.php';
    
    /*
     * This page neatly displays all the items in the current basket.
     * It also displays all the previous Baskets if the user is logged in.
     */
    
    $template->setPage('basket');
    $template->setPageTitle('My Basket');
    $template->loadPage();
?>
