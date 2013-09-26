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
        }
    }
?>

<!DOCTYPE HTML>
<html>
  <head>
        <style type="text/css">
 
            body {font-family:Arial, Sans-Serif;}
 
            /*container {}*/
 
            form label {display:inline-block; width:140px;}
          
            .line   {
                padding: 5px;
            }
        
 
        </style>
    </head>
<body>


    <div id="header" style="background-color:#24A500;">

    <h1 style="margin-bottom:0;">HJRKART.COM</h1></div>

    <div id="menu" style="background-color:#FFFFFF;height:410px;width:50px;float:left;">

    </div>


    <div id="container" style="background-color:#EEEEEE;width:500px; margin:0 auto;">
        <form method="POST" autocomplete="on" action="login.php">
            <p style="text-align: center; font-size: 25px"> Login </p>
            <div class="line" align="center"><label for="name">Username *: </label><input type="text" id="name" name="uname"/></div><br>
            <div class="line" align="center"><label for ="password">Password *: </label><input type="password" id="password" name="upass" /></div><br>

            <div align="center">
                <br>
                <?php
                    if(isset($message)) {
                        echo $message.'<br />';
                    }
                    echo '<br />';
                ?>
                <br>
                <br>
                <div style="">
                    <input type="submit" value="Login" name="login">
                </div>
            </div>
        </form>
    </div>

    <div id="footer" style="background-color:#FFA500;clear:both;text-align:center;">
    </div>

</body>
</html>

