<?php
/**
* Plugin Name: WP Floating Content
* Plugin URI: http://test.com
* Description: Create Smart WP sticky sidebar
* Version: 1.0.0
* Author: @techiethemastermind
* Author URI: http://techiethemastermind.com
* License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WFC_PLUGIN_VERSION', '1.0.0' );
define( 'WFC_DB_VERSION', '1.0.0' );
define( 'WFC_PLUGIN_DOMAIN', 'wp-floating-content' );
define( 'WFC_TABLE_NAME',  'wfc_options');

class WP_FLOATING_CONTENT {

    public function __construct () {

        include_once('classes/wfc-admin.php');
        include_once('classes/wfc-front.php');
        add_action('init', array( &$this, 'wfc_plugin_init'));
        add_action('init', array( &$this, 'wfc_assets_load'));
    }

    public static function wfc_plugin_init () {
        register_activation_hook( __FILE__, 'wfc_plugin_active_hook' );
        register_uninstall_hook(__FILE__, 'wfc_plugin_uninstall_hook');
        add_action('admin_menu', array(__class__, 'wfc_admin_menu'));
    }

    /**
     * Plugin action when plugin activated
     */
    public static function wfc_plugin_active_hook () {

        global $wpdb;
        $table_name = $wpdb->prefix . WFC_TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();

        if($wpdb->query( $wpdb->prepare("SHOW TABLES LIKE '%s'", $table_name) ) == 0 ) {
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name varchar(100) NOT NULL,
                value varchar(100) DEFAULT '' NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
        }

        add_option( 'wfc_db_version', WFC_DB_VERSION );
    }

    /**
     * Plugin action when plugin deleted
     */
    public static function wfc_plugin_uninstall_hook () {
        global $wpdb;
        $table_name = $wpdb->prefix . WFC_TABLE_NAME;
        $wpdb->query( "DROP TABLE IF EXISTS " . $table_name );
        delete_option("my_plugin_db_version");
    }

    /**
     * Adding Admin Menu action
     */
    public static function wfc_admin_menu() {

        $page = add_menu_page(
           __( 'WP Floating Content', 'textdomain' ),
            'WFC Manager',
            'manage_options',
            'wp_floating_content',
            array(__class__, 'wfc_manager_func'),
            'dashicons-align-center',
            6
       );
    }

    /**
     * Load JS and CSS for plugin
     */
    public function wfc_assets_load () {

        wp_enqueue_style('wfc_main_style', plugin_dir_url(__FILE__) . '/assets/css/wfc_style.css', false, WFC_PLUGIN_VERSION, 'all');
        wp_enqueue_style('wfc_select2_style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', false, WFC_PLUGIN_VERSION, 'all');
        wp_enqueue_script( 'wfc_main_script', plugin_dir_url(__FILE__) . '/assets/js/wfc_script.js', array( 'jquery' ), WFC_PLUGIN_VERSION, true );
        wp_enqueue_script( 'wfc_select2_script', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), WFC_PLUGIN_VERSION, true );
    }

    /**
     * Admin UI
     */
    public static function wfc_manager_func () {
        do_action('wfc_admin_output');
    }
}

new WP_FLOATING_CONTENT();
