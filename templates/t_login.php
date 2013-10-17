<!-- This is the main Login page. -->
<div id="mBody" >
    <form method="POST" autocomplete="on" action="login.php">
        <div class="inset">
        <p>
          <label for="username">USERNAME : </label>
          <input type="username" name="uname" id="username" autofocus="on" placeholder=" Enter your username ">
        </p>
        <p>
          <label for="password">PASSWORD</label>
          <input type="password" name="upass" id="password" placeholder=" Password here ">
        </p>
        </div>
        <input type="submit" name="login" id="go" value=" Login ">
        <div align="center">
            <br>
            <?php
                $this->printVar('message');
            ?>
            <br>
            <br>
        </div>
    </form>
</div>