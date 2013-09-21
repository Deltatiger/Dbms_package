<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// This is the backup of the SQL db tables.
// This is the Sub Category Table.
$sql = "CREATE TABLE dbms_sub_category  (
            `sc_name`         varchar(35),
            `sc_belongs_to`   varchar(35),
            PRIMARY KEY(`sc_name`, `sc_belongs_to`),
            FOREIGN KEY(`sc_belongs_to`) REFERENCES `dbms_package`.`dbms_category`(`c_name`)
    );";

// This is the User Table.
$sql = "CREATE TABLE dbms_user  (
            `user_name`         varchar(30),
            `user_name_clean`   varchar(30) UNIQUE,
            `user_pass`         text,
            `user_id`           int NULL AUTO_INCREMENT,
            `user_dob`          DATE,
            `user_email`        text NOT NULL,
            PRIMARY KEY (`user_id`)
    );";

// This is the session Table.
$sql = "CREATE TABLE dbms_session   (
            `session_id`            VARCHAR(60),
            `session_user_id`       int,
            `session_create_time`   DATETIME,
            `session_last_active`   DATETIME,
            `session_user_type`     int,
            `session_create_ip`     text,
            `session_browser`       text,
            `session_login_stat`    int,
            PRIMARY KEY (`session_id`),
            FOREIGN KEY (`session_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            CONSTRAINT `S_Login_stat_check` CHECK (`session_login_stat` IN (0, 1))
    );";

// This is the seller Table.
$sql = "CREATE TABLE dbms_seller_ratings    (
            `seller_user_id`    int,
            `seller_approved`   int DEFAULT 0,
            `seller_avg_rating` real DEFAULT 0,
            PRIMARY KEY (`seller_user_id`),
            FOREIGN KEY (`seller_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            CONSTRAINT `seller_avg_rating` CHECK (`seller_avg_rating` BETWEEN 0 AND 5)
    );";

// This is the Item table.
$sql = "CREATE TABLE dbms_item  (
            `item_name`         varchar(40),
            `item_id`           int NULL AUTO_INCREMENT,
            `item_sub_category` varchar(35),
            `item_seller_id`    int,
            `item_stock`        int,
            `item_avg_rating`   real DEFAULT 0,
            PRIMARY KEY (`item_id`),
            FOREIGN KEY (`item_seller_id`) REFERENCES `dbms_package`.`dbms_seller`(`seller_user_id`),
            CONSTRAINT `item_ratings_check` CHECK (`item_avg_rating` BETWEEN 0 AND 5)
    );";

// This is the Basket Table
$sql = "CREATE TABLE dbms_basket    (
            `basket_id`         int NULL AUTO_INCREMENT,
            `basket_user_id`    int,
            `basket_clear`      int DEFAULT 1,
            PRIMARY KEY (`basket_id`),
            FOREIGN KEY (`basket_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            CONSTRAINT `basket_clear_check` CHECK (`basket_clear` IN (0, 1))
    );";
        
// This is the relation that connects Basket and Items
$sql = "CREATE TABLE dbms_basket_contains   (
            `basket_id`         int NOT NULL,
            `basket_item_id`    int NOT NULL,
            PRIMARY KEY (`basket_id`, `basket_item_id`)
            FOREIGN KEY (`basket_id`) REFERENCES `dbms_package`.`dbms_basket`(`basket_id`),
            FOREIGN KEY (`basket_item_id`) REFERENCES `dbms_package`.`dbms_item`(`item_id`)
    );";

//This is the Ratings Table
$sql = "CREATE TABLE dbms_ratings   (
            `rating_user_id`    int NOT NULL,
            `rating_item_id`    int NOT NULL,
            `rating_value`      REAL,
            PRIMARY KEY (`rating_user_id`, `rating_item_id`),
            FOREIGN KEY (`rating_user_id`) REFERENCES `dbms_package`.`dbms_user`(`user_id`),
            FOREIGN KEY (`rating_item_id`) REFERENCES `dbms_package`.`dbms_item`(`item_id`),
            CONSTRAINT `ratings_value_check` CHECK (`rating_value` BETWEEN 0 AND 5)
    );";

// This is the Payments table
$sql = "CREATE TABLE dbms_payments (
            `payment_basket_id`     int NOT NULL,
            `payment_id`            int NULL AUTO_INCREMENT,
            `payment_time`          DATETIME DEFAULT NOW(),
            PRIMARY KEY (`payment_id`),
            CONSTRAINT `Payment_Basket_UNIQUE` UNIQUE (`payment_basket_id`),
    );";
?>
