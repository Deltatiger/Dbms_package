<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            //This is the main file.
            include 'includes/config.php';
            
           $sql = "CREATE TABLE dbms_payments (
            `payment_basket_id`     int NOT NULL UNIQUE,
            `payment_id`            int NULL AUTO_INCREMENT,
            `payment_time`          DATETIME NOT NULL,
            PRIMARY KEY (`payment_id`)
    );";
            $query = $db->query($sql);
            echo $query;
        ?>
    </body>
</html>
