<?php

/**
 * @package Hobo Video
 */

/*

Plugin Name: Mapper

Plugin URI: https://hobo.video/

Description: The plugin will display the <strong>user data</strong> with the <strong>related products</strong>. It allows you to <strong>add products</strong> to the user content and each product with text and file url. To get started: activate Mapper plugin and then go to your Mapper click Mapper menu item to display the main page to add products.

Version: 1.0.0

Author: Amira Mustafa

Copyright: 2021 Amira Mustafa

Author URI: https://hobo.video/

License: GPLv2 or later

*/

if (!defined('ABSPATH')) {
    exit; // Exit if user accessed the file directly
}


/**
 * Register Scripts and styles.
 */

add_action('wp_print_styles', 'add_my_plugin_stylesheet');
function add_my_plugin_stylesheet()
{
    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    wp_register_style('mapper_front', plugin_dir_url(__FILE__) . 'assets/css/mapper-front.css');
    wp_enqueue_style('mapper_front');
}

add_action('admin_enqueue_scripts', 'setting_up_scripts');
function setting_up_scripts()
{

    /**
     * * Styles.
     */
    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    wp_register_style('mapper_style', plugin_dir_url(__FILE__) . 'assets/css/mapper.css');
    wp_enqueue_style('mapper_style');
    wp_register_style('mapper_style_chosen', 'https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css');
    wp_enqueue_style('mapper_style_chosen');

    /**
     * * Scripts.
     */
    wp_enqueue_script('mapper_scripts_externallink', plugin_dir_url(__FILE__) . 'assets/scripts/externallinks.js', array('jquery'));
    wp_enqueue_script('mapper_scripts_jquery', plugin_dir_url(__FILE__) . 'assets/scripts/jquery.min.js', array('jquery'));
    wp_enqueue_script('mapper_scripts_select', plugin_dir_url(__FILE__) . 'assets/scripts/selectjquery.min.js', array('jquery'));
    wp_enqueue_script('mapper_scripts_ajax', plugin_dir_url(__FILE__) . 'assets/scripts/chosen.jquery.min.js', array('jquery'));


}

/**
 * Includes functions.
 */
include(plugin_dir_path(__FILE__) . 'includes/mapper-db-init.php');
include(plugin_dir_path(__FILE__) . 'includes/mapper-menu-item.php');


// Act on plugin activation
register_activation_hook(__FILE__, "activate_myplugin");

// Act on plugin de-activation
register_deactivation_hook(__FILE__, "deactivate_myplugin");

// Activate Plugin
function activate_myplugin()
{
    // Insert DB Tables
    init_db_mapper();
}

// De-activate Plugin
function deactivate_myplugin()
{
    // Execute tasks on Plugin de-activation
}

add_shortcode('mapper_users', 'mapper_users_query');
function mapper_users_query()
{

// WP Globals
    global $table_prefix, $wpdb;

// Define Table Names
    $usersTable = $table_prefix . 'mapper_users';
    $productsTable = $table_prefix . 'mapper_products';
    $userProductTable = $table_prefix . 'mapper_user_product';
    $externalLinksTable = $table_prefix . 'mapper_external_links';


// Display All users Activities
    echo "<div class='container users-activities'>";

    $result = $wpdb->get_results("SELECT * FROM $usersTable");
    foreach ($result as $print) {
        echo '<div class="row">';
        echo '<div class="mapper-user">' . $print->user_name . '</div>';
        echo '<div class="mapper-time">' . $print->time . '</div><br/>';
        echo '<div class="mapper-user-activity">' . $print->activity . '</div>';
        echo '<div class="mapper-user-products"> <div class="title">Products:</div>';
        $productResult = $wpdb->get_results("SELECT * FROM $productsTable INNER JOIN $userProductTable ON $productsTable.product_id=$userProductTable.product_id WHERE  $userProductTable.user_id = $print->user_id");
        foreach ($productResult as $productprint) {
            echo '<div class="mapper-user-product">' . $productprint->product_name . '</div>';
        }
        echo '</div>';

        echo '<div class="mapper-user-links"><div class="title">Links:</div>';
        $linksResult = $wpdb->get_results("SELECT * FROM $externalLinksTable WHERE  user_id = $print->user_id");
        foreach ($linksResult as $linkprint) {
            echo '<div class="mapper-user-link"><a target="_blank" href="' . $linkprint->external_url . '"><img src="' . plugin_dir_url(__FILE__) . '/assets/media/shop.png">' . $linkprint->external_desc . '</a></div>';
        }
        echo '</div>';


        echo '</div>';

    }


    echo "</div>";
}

?>
