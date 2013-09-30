<?php

/*
 * Page that is used to change the registered User from Regular User to a Seller.
 */
    include 'includes/config.php';
    if ( !$session->isLoggedIn())   {
        //We need a logged in user to register him as a seller.
        header('Location:index.php');
    }
    //We have to check the user already has a pending / confirmed status of a seller.
    $userId = $session->getUserId();
    $sql = "SELECT `seller_approved` FROM `{$db->name()}`.`dbms_seller_info` WHERE `seller_user_id` = '{$userId}'";
    $query = $db->query();
    if (mysql_num_rows($query) > 0) {
        //We alreadty have a request from the user. We just display the status.
        $result = mysql_fetch_result($query);
        $message = 'Your Seller Status is : '. ($result->seller_approved == '0' ? 'Pending' : 'Approved').'.' ;
        mysql_free_result($query);
    } else {
        if (isset($_POST['register']))  {
            // This means that we have to get the stuff from DB.
            $sql = "INSERT INTO `{$db->name()}`.`dbms_seller_info` (`seller_user_id`) VALUES ('{$userId}')";
            $query = $db->query($sql);
            $message = 'Your request is under consideration. Please visit Later.';
        } else {
            $message = "<br><br><div><input type=\"submit\" value=\"Register As Seller\" name=\"register\"></div>";
        }
    }
    $template->setPageTitle('Registration Page.');
?>
<!--
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
        <form method="POST" autocomplete="on" action="sellerRegistration.php">
            <p style="text-align: center; font-size: 25px"> Login </p>

            <div align="center">
                <br>
                <?php
                    if(isset($message)) {
                        echo $message.'<br />';
                    }
                    echo '<br />';
                ?>
            </div>
        </form>
    </div>

    <div id="footer" style="background-color:#FFA500;clear:both;text-align:center;">
    </div>

</body>
</html>
-->
