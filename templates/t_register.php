<div id ="mBody">
    <div>
        <form method="POST" autocomplete="on" action="registration_page.php">
            <div class="inset">
            <p>
              <label for="username"> USERNAME : </label>
              <input type="username" name="username" id="username"  placeholder=" Enter a Username ">
            </p>
            <p>
              <label for="password"> PASSWORD : </label>
              <input type="password" name="password" id="password" placeholder=" Enter a Password ">
            </p>
            <p>
              <label for="email"> E-MAIL : </label>
              <input type="email" name="email" id="email" placeholder=" E-Mail ID here ">
            </p>
            </div>
            <input type="submit" name="register" id="go" value=" Register ">
            <div align="center">
                <br>
                <?php 
                    $this->printVar('message');
                    echo '<br />';
                ?>
                <br>
                <br>
            </div>
        </form>
    </div>
</div>