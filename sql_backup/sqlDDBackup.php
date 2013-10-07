<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// This is the backup of the SQL db tables.
include '../includes/mydb.php';
$db = new DB();

// This isthe Category Table.
$sql[0] = "CREATE TABLE dbms_category  (
            `c_name`        varchar(35),
            `c_id`          int NULL AUTO_INCREMENT,
            PRIMARY KEY (`c_id`)
    );";
// This is the Sub Category Table.
$sql[1] = "CREATE TABLE dbms_sub_category  (
            `sc_name`         varchar(35) UNIQUE,
            `sc_id`           int NULL AUTO_INCREMENT,
            `sc_belongs_to`   int,
            PRIMARY KEY(`sc_id`),
            FOREIGN KEY(`sc_belongs_to`) REFERENCES `dbms_package`.`dbms_category`(`c_id`)
    );";

// This is the User Table.
$sql[2] = "CREATE TABLE dbms_user  (
            `user_name`         varchar(30),
            `user_name_clean`   varchar(30) UNIQUE,
            `user_pass`         text,
            `user_id`           int NULL AUTO_INCREMENT,
            `user_dob`          DATE,
            `user_email`        text NOT NULL,
            PRIMARY KEY (`user_id`)
    );";

// This is the Basket Table
$sql[3] = "CREATE TABLE dbms_basket    (
            `basket_id`         int NULL AUTO_INCREMENT,
            `basket_user_id`    int,
            `basket_clear`      int DEFAULT 1,
            PRIMARY KEY (`basket_id`),
            FOREIGN KEY (`basket_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            CONSTRAINT `basket_clear_check` CHECK (`basket_clear` IN (0, 1))
    );";

// This is the session Table.
$sql[4] = "CREATE TABLE dbms_session   (
            `session_id`            VARCHAR(60),
            `session_user_id`       int,
            `session_create_time`   int,
            `session_last_active`   int,
            `session_user_type`     int,
            `session_create_ip`     text,
            `session_browser`       text,
            `session_login_stat`    int DEFAULT 0,
            `session_basket_id`     int,
            PRIMARY KEY (`session_id`),
            FOREIGN KEY (`session_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            FOREIGN KEY (`session_basket_id`) REFERENCES `dbms_package`.`dbms_basket`(`basket_id`)
    );";

// This is the seller Table.
$sql[5] = "CREATE TABLE dbms_seller_info    (
            `seller_user_id`    int,
            `seller_approved`   int DEFAULT 0,
            `seller_avg_rating` real DEFAULT 0,
            PRIMARY KEY (`seller_user_id`),
            FOREIGN KEY (`seller_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            CONSTRAINT `seller_avg_rating` CHECK (`seller_avg_rating` BETWEEN 0 AND 5)
    );";

// This is the Item table.
$sql[6] = "CREATE TABLE dbms_item  (
            `item_name`         varchar(40),
            `item_id`           int NULL AUTO_INCREMENT,
            `item_sub_category` int,
            `item_seller_id`    int,
            `item_price`        double,
            `item_stock`        int,
            `item_avg_rating`   real DEFAULT 0,
            PRIMARY KEY (`item_id`),
            FOREIGN KEY (`item_sub_category`) REFERENCES `dbms_package`.`dbms_sub_category`(`sc_id`),
            FOREIGN KEY (`item_seller_id`) REFERENCES `dbms_package`.`dbms_seller_info`(`seller_user_id`),
            CONSTRAINT `item_ratings_check` CHECK (`item_avg_rating` BETWEEN 0 AND 5)
    );";

// This is the relation that connects Basket and Items
$sql[7] = "CREATE TABLE dbms_basket_contains   (
            `basket_id`         int NOT NULL,
            `basket_item_id`    int NOT NULL,
            PRIMARY KEY (`basket_id`, `basket_item_id`),
            FOREIGN KEY (`basket_id`) REFERENCES `dbms_package`.`dbms_basket`(`basket_id`),
            FOREIGN KEY (`basket_item_id`) REFERENCES `dbms_package`.`dbms_item`(`item_id`)
    );";

//This is the Ratings Table
$sql[8] = "CREATE TABLE dbms_ratings   (
            `rating_user_id`    int NOT NULL,
            `rating_item_id`    int NOT NULL,
            `rating_value`      REAL,
            `rating_text`       text,
            PRIMARY KEY (`rating_user_id`, `rating_item_id`),
            FOREIGN KEY (`rating_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            FOREIGN KEY (`rating_item_id`) REFERENCES `dbms_package`.`dbms_item`(`item_id`),
            CONSTRAINT `ratings_value_check` CHECK (`rating_value` BETWEEN 0 AND 5)
    );";

// This is the Payments table
$sql[9] = "CREATE TABLE dbms_payments (
            `payment_basket_id`     int NOT NULL UNIQUE,
            `payment_id`            int NULL AUTO_INCREMENT,
            `payment_time`          int NOT NULL,
            PRIMARY KEY (`payment_id`),
            FOREIGN KEY (`payment_basket_id`) REFERENCES `dbms_package`.`dbms_basket`(`basket_id`)
    );";

for ( $i = 0 ; $i <= 9 ; $i++)   {
    $query = $db->query($sql[$i]);
    echo $query;
}
?>
