<?php
    //This is the main file.
    include 'includes/config.php';
    if ( $session->isLoggedIn())    {
        echo 'You are Logged in as '.$session->getUserNameFromSession();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="resources/css/main.css" />
    </head>
    <body>
        <div id = "container">
            <div id = "centerAlign">
                G R A S S <br />
            </div>
        </div>
    </body>
</html>
