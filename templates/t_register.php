<div id ="mBody">
    <div>
        <form method="POST" autocomplete="on" action="registration_page.php">
            <div class="inset">
            <p>
              <label for="name"> NAME : </label>
              <input type="name" name="name" id="name" placeholder=" Enter your Name " autofocus="on">
            </p>
              <p>
              <label for="email"> E-MAIL : </label>
              <input type="email" name="email" id="email" placeholder=" E-Mail ID here ">
            </p>

            <p>
              <label for="username"> USERNAME : </label>
              <input type="username" name="username" id="username"  placeholder=" Select a username ">
            </p>
            <p>
              <label for="password"> PASSWORD : </label>
              <input type="password" name="password" id="password" placeholder=" Password here ">
            </p>

            </div>
            <input type="submit" name="go" id="go" value=" Register ">
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