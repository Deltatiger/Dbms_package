<?php

    include_once 'includes/config.php';
    
    if( !$session->isLoggedIn() || !$session->isSeller())   {
        //Kick the user out if not registered or not a registered seller.
        header('Location:index.php');
    }
   
    $template->setPage('shome');
    $template->setPageTitle('Seller | Home Page');
    $template->loadPage();
    
?>
