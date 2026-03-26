<?php
/**
 * Plugin Name: Gym Community Plugin
 * Plugin URI: https://github.com/jouwgebruikersnaam/gym-community-plugin
 * Description: Custom plugin voor Gym Community website met activiteiten, reviews en inschrijfsysteem
 * Version: 1.0.0
 * Author: Jouw Naam
 * Author URI: https://jouwwebsite.nl
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gym-community-plugin
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'GYM_COMMUNITY_VERSION', '1.0.0' );
define( 'GYM_COMMUNITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GYM_COMMUNITY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class Gym_Community_Plugin {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once GYM_COMMUNITY_PLUGIN_DIR . 'includes/class-gym-activities.php';
        require_once GYM_COMMUNITY_PLUGIN_DIR . 'includes/class-gym-reviews.php';
        require_once GYM_COMMUNITY_PLUGIN_DIR . 'includes/class-gym-registrations.php';
        require_once GYM_COMMUNITY_PLUGIN_DIR . 'includes/class-gym-shortcodes.php';
        require_once GYM_COMMUNITY_PLUGIN_DIR . 'admin/class-gym-admin.php';
    }

    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'gym-community-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 
            'gym-community-plugin-style', 
            GYM_COMMUNITY_PLUGIN_URL . 'assets/css/gym-community.css', 
            array(), 
            GYM_COMMUNITY_VERSION 
        );

        wp_enqueue_script( 
            'gym-community-plugin-script', 
            GYM_COMMUNITY_PLUGIN_URL . 'assets/js/gym-community.js', 
            array( 'jquery' ), 
            GYM_COMMUNITY_VERSION, 
            true 
        );

        wp_localize_script( 'gym-community-plugin-script', 'gymCommunity', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'gym_community_nonce' ),
        ) );
    }

    public function enqueue_admin_scripts( $hook ) {
        wp_enqueue_style( 
            'gym-community-admin-style', 
            GYM_COMMUNITY_PLUGIN_URL . 'assets/css/admin.css', 
            array(), 
            GYM_COMMUNITY_VERSION 
        );

        wp_enqueue_script( 
            'gym-community-admin-script', 
            GYM_COMMUNITY_PLUGIN_URL . 'assets/js/admin.js', 
            array( 'jquery' ), 
            GYM_COMMUNITY_VERSION, 
            true 
        );
    }

    public function activate() {
        Gym_Activities::register_post_type();
        Gym_Reviews::register_post_type();
        
        flush_rewrite_rules();
        
        $this->create_tables();
        
        if ( ! get_option( 'gym_community_version' ) ) {
            add_option( 'gym_community_version', GYM_COMMUNITY_VERSION );
        }
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'gym_registrations';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            activity_id bigint(20) NOT NULL,
            user_name varchar(255) NOT NULL,
            user_email varchar(255) NOT NULL,
            user_phone varchar(50) DEFAULT NULL,
            registration_date datetime DEFAULT CURRENT_TIMESTAMP,
            status varchar(20) DEFAULT 'pending',
            notes text DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY activity_id (activity_id),
            KEY user_email (user_email)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

function gym_community_plugin() {
    return Gym_Community_Plugin::get_instance();
}

gym_community_plugin();
