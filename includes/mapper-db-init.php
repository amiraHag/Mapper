<?php
// Initialize DB Tables
function init_db_mapper()
{
// WP Globals
    global $table_prefix, $wpdb;

// Define Table Names
    $usersTable = $table_prefix . 'mapper_users';
    $productsTable = $table_prefix . 'mapper_products';
    $userProductTable = $table_prefix . 'mapper_user_product';
    $externalLinksTable = $table_prefix . 'mapper_external_links';

// Include Upgrade Script
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');



/**
 * 
 * User Table.
 * 
 */
 
// Create Users Table if not exist
    if ($wpdb->get_var("show tables like '$usersTable'") != $usersTable) {

// Query - Create User Table
        $userSql = "CREATE TABLE $usersTable (
user_id mediumint(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
user_name text NOT NULL,
activity longtext NOT NULL
) $charset_collate;";


// Add Table to DB
        dbDelta($userSql);
    }



/**
 * 
 * Products Table.
 * 
 */
 
// Create Product Table if not exist
    if ($wpdb->get_var("show tables like '$productsTable'") != $productsTable) {

// Query - Create Table
        $productSql = "CREATE TABLE $productsTable (
product_id mediumint(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
product_name text NOT NULL,
product_desc text NOT NULL
) $charset_collate;";


// Add Table to DB
        dbDelta($productSql);
    }



/**
 * 
 * User Product Table.
 * 
 */
 
// Create User Product Table if not exist
    if ($wpdb->get_var("show tables like '$userProductTable'") != $userProductTable) {

// Query - Create Table
        $userProductSql = "CREATE TABLE $userProductTable (
id mediumint(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id mediumint(11) UNSIGNED NOT NULL,
product_id mediumint(11) UNSIGNED NOT NULL,
FOREIGN KEY  (user_id) REFERENCES $usersTable(user_id),
FOREIGN KEY  (product_id) REFERENCES $productsTable(product_id)
) $charset_collate;";


// Add Table to DB
        dbDelta($userProductSql);
    }



/**
 * 
 * External Links Table.
 * 
 */
 
// Create External Links Table if not exist
    if ($wpdb->get_var("show tables like '$externalLinksTable'") != $externalLinksTable) {

// Query - Create Table
        $externalLinkSql = "CREATE TABLE $externalLinksTable (
external_link_id mediumint(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id mediumint(11) UNSIGNED NOT NULL,
external_url text NOT NULL,
external_desc text NOT NULL,
FOREIGN KEY  (user_id) REFERENCES $usersTable(user_id)
) $charset_collate;";


// Add Table to DB
        dbDelta($externalLinkSql);
    }

}
