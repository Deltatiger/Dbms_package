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
            
            $sql = "CREATE TABLE dbms_item  (
            `item_name`         varchar(40),
            `item_id`           int NULL AUTO_INCREMENT,
            `item_sub_category` varchar(35),
            `item_seller_id`    int,
            `item_stock`        int,
            `item_avg_rating`   real DEFAULT 0,
            PRIMARY KEY (`item_id`),
            FOREIGN KEY (`item_seller_id`) REFERENCES `dbms_package`.`dbms_seller`(`seller_user_id`)
    );";
            $query = $db->query($sql);
            echo $query;
        ?>
    </body>
</html>
