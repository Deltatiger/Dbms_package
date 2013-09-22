<?php

/*
 * This contains all the common functions.
 */

function generateRandString($length)	{
    //This generates a random string of $length charecters long
    $randomString = '';
    $range = str_split('abcdefghijklmnopqrstuvwxyz1234567890<>?:"{}!@#$%^&*()_+', '1');
    for($i = 0; $i < $length; $i++)	{
        $randomString .= array_rand($range);
    }
    return $randomString;
}

function registerUser($username, $password, $dobDay, $dobMonth, $dobYear , $email)  {
    global $db;
    /*
     * @desc : This function is used to register a new user and login in the current User.
     *          We check 2 things namely username repetition and email repetition
     */
    $usernameClean = strtolower(trim($username));
    $emailClean = strtolower(trim($email));
    $sql = "SELECT `user_name`, `user_email`, `user_id` FROM `{$db->name()}`.`dbms_user` WHERE LOWER(`user_name`) = '{$usernameClean}' || LOWER(`user_email`) = '{$emailClean}'";
    $query = $db->query($sql);
    if ( ($dobMonth < 0 && $dobMonth > 12) || ($dobDay < 0 && $dobDay > 31) || $dobYear > 2015 )    {
        return false;
    }
    if (mysql_num_rows($query))     {
        //We already a row with this things.
        mysql_free_result($query);
        return false;
    } else {
        //We seem to be free of the user. We proceed to register him.
        $usernameClean = trim($username);
        $emailClean = trim($email);
        //This is the crypt for hashing the password.
        $passwordHash = crypt($password);
        $dobFormat = "{$dobYear}-{$dobMonth}-{$dobDay}";
        $sql = "INSERT INTO `{$db->name()}`.`dbms_user`(`user_name`,`user_pass`,`user_dob`,`user_email`) VALUES ('{$usernameClean}','{$passwordHash}','{$dobFormat}','{$emailClean}')";
        $query = $db->query($sql);
    }
    return true;
}   
?>
