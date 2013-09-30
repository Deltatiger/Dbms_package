<div id ="mBody">
    <div class="centerAlign" style="background-color:#EEEEEE;width:500px; margin:0 auto;">
        <form method="POST" autocomplete="on" action="registration_page.php">
            <p style="text-align: center; font-size: 25px">Registration </p>
            <div class="line">
                <label for="name">Name *: </label> 
                <input type="text" id="name" name="uname"/>
            </div>
            <br>
            <div class="line">
                <label for="birthday">DOB  (YYYY MM DD) :</label>
                <input type="text" id="birthday" name="udobY" class="shortInput"/>
                <input type="text" id="birthday" name="udobM" class="shortInput"/>
                <input type="text" id="birthday" name="udobD" class="shortInput"/>
            </div><br>
            <div class="line">
                <label for="email">E-mail *: </label>
                <input type="email" id="email" name="uemail"/>
            </div>
            <br>
            <div class="line">
                <label for ="password">Password *: </label>
                <input type="password" id="password" name="upass" />
            </div>
            <br>
            <div align="center">
                <br>
                <?php 
                    if($this->isVarSet('message')) {
                        echo $this->getVariable('message').'<br />';
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
</div>