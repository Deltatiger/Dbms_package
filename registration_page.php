<?php
    //This page is solely meant for registration.
    include 'includes/config.php';
    
    if ($session->isLoggedIn())   {
        header('Location:index.php');
    }

    if (isset($_POST['register'])) {
        //We have a submit button clck
        $username = $_POST['username'];
        $userpass = $_POST['password'];
        $userEmail = $_POST['email'];
        
        if (registerUser($username, $userpass, $userEmail))    {
            //This is the pass condition.
            $session->login($username, $userpass);
            header('Location:index.php');
        } else {
            $template->setTemplateVar('message', "Error Occured. Try again with Valid Data.");
        }
    }
    
    $template->setPageTitle('Registration Page.');
    $template->setPage('register');
     
    $template->loadPage();
?>