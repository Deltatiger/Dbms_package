<!-- This is the main Login page. -->
<div id="mBody" >
    <form method="POST" autocomplete="on" action="login.php">
        <p class="centerTextAlign"> Login </p>
        <div class="line" align="center">
            <label for="name">Username *: </label>
            <input type="text" id="name" name="uname"/>
        </div>
        <br>
        <div class="line" align="center">
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
            ?>
            <br>
            <br>
            <div style="">
                <input type="submit" value="Login" name="login">
            </div>
        </div>
    </form>
</div>