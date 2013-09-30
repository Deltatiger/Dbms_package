<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    include 'includes/config.php';
    
    if ($session->isLoggedIn())   {
        header('Location:index.php');
    }
    
    if ( isset($_POST['login']))    {
        $username = mysql_real_escape_string(trim($_POST['uname']));
        $userpass = mysql_real_escape_string(trim($_POST['upass']));
        if ( $session->login($username, $userpass)) {
            header('Location:index.php');
        } else {
            $message = 'Invalid Credentials.';
            $template->setTemplateVar('message', $message);
        }
    }
    $template->setPage('login');
    $template->setPageTitle('Login Page');
    
    $template->loadPage();
?>