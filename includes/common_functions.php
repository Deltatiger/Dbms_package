<?php

/*
 * This contains all the common functions.
 */

function generateRandString($length)	{
    //This generates a random string of $length charecters long
    $randomString = '';
    $range = 'abcdefghijklmnopqrstuvwxyz1234567890<>?:"{}!@#$%^&*()_+';
    for($i = 0; $i < $length; $i++)	{
        @$randomString .= array_rand($range);
    }
    return $randomString;
}
?>
