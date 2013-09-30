<?php
    //This page is solely meant for registration.
    include 'includes/config.php';
    
    if ($session->isLoggedIn())   {
        header('Location:index.php');
    }

    if (isset($_POST['submit'])) {
        //We have a submit button clck
        $username = $_POST['uname'];
        $userpass = $_POST['upass'];
        $userEmail = $_POST['uemail'];
        $userDoBY = intval($_POST['udobY']);
        $userDoBM = intval($_POST['udobM']);
        $userDoBs = intval($_POST['udobD']);
        
        if (registerUser($username, $userpass, $userDoBs, $userDoBM, $userDoBY, $userEmail))    {
            //This is the pass condition.
            $session->login($username, $userpass);
            header('Location:index.php');
        } else {
            $template->setTemplateVar($message, "Error Occured. Try again with Valid Data.");
        }
    }
    
    $template->setPageTitle('Registration Page.');
    $template->setPage('register');
     
    $template->loadPage();
?>