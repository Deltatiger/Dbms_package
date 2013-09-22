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
        $userDoBY = intval($_POST['udoby']);
        $userDoBM = intval($_POST['udobm']);
        $userDoBs = intval($_POST['udobs']);
        
        if (registerUser($username, $userpass, $userDoBs, $userDoBM, $userDoBY, $userEmail))    {
            //This is the pass condition.
            header('Location:index.php');
        } else {
            $message = "Error Occured. Try again with Valid Data.";
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
        <form method="POST" autocomplete="on" action="registration_page.php">
            <p style="text-align: center; font-size: 25px">Registration </p>
            <div class="line"><label for="name">Name *: </label><input type="text" id="name" name="uname"/></div><br>
            <div class="line"><label for="birthday">DOB  (YYYY MM DD) :</label>
                <input type="date" id="birthday" name="dobY" style="width:50px;"/>
                <input type="date" id="birthday" name="dobM" style="width:50px;"/>
                <input type="date" id="birthday" name="dobD" style="width:50px;"/>
            </div><br>
            <div class="line"><label for="email">E-mail *: </label><input type="email" id="email" name="uemail"/></div><br>
            <div class="line"><label for ="password">Password *: </label><input type="password" id="password" name="upass" /></div><br>

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
                    <input type="submit" value="Submit" name="submit">
                </div>
            </div>
        </form>
    </div>

    <div id="footer" style="background-color:#FFA500;clear:both;text-align:center;">
    </div>

</body>
</html>
