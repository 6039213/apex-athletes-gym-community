<?php
/**
 * Gym Admin Settings
 *
 * @package Gym_Community_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_settings_page() {
        add_menu_page(
            __( 'Gym Community Settings', 'gym-community-plugin' ),
            __( 'Gym Community', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-heart',
            30
        );

        add_submenu_page(
            'gym-community-settings',
            __( 'Settings', 'gym-community-plugin' ),
            __( 'Settings', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-settings',
            array( $this, 'render_settings_page' )
        );

        add_submenu_page(
            'gym-community-settings',
            __( 'Documentation', 'gym-community-plugin' ),
            __( 'Documentation', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-docs',
            array( $this, 'render_docs_page' )
        );
    }

    public function register_settings() {
        register_setting( 'gym_community_settings', 'gym_community_email_notifications' );
        register_setting( 'gym_community_settings', 'gym_community_admin_email' );
        register_setting( 'gym_community_settings', 'gym_community_auto_approve_reviews' );
        register_setting( 'gym_community_settings', 'gym_community_registration_limit' );

        add_settings_section(
            'gym_community_general',
            __( 'General Settings', 'gym-community-plugin' ),
            array( $this, 'general_section_callback' ),
            'gym-community-settings'
        );

        add_settings_field(
            'gym_community_email_notifications',
            __( 'Email Notifications', 'gym-community-plugin' ),
            array( $this, 'email_notifications_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        add_settings_field(
            'gym_community_admin_email',
            __( 'Admin Email', 'gym-community-plugin' ),
            array( $this, 'admin_email_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        add_settings_field(
            'gym_community_auto_approve_reviews',
            __( 'Auto-approve Reviews', 'gym-community-plugin' ),
            array( $this, 'auto_approve_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        add_settings_field(
            'gym_community_registration_limit',
            __( 'Registration Limit per User', 'gym-community-plugin' ),
            array( $this, 'registration_limit_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );
    }

    public function general_section_callback() {
        echo '<p>' . __( 'Configure general settings for the Gym Community plugin.', 'gym-community-plugin' ) . '</p>';
    }

    public function email_notifications_callback() {
        $value = get_option( 'gym_community_email_notifications', '1' );
        ?>
        <label>
            <input type="checkbox" name="gym_community_email_notifications" value="1" <?php checked( $value, '1' ); ?>>
            <?php _e( 'Send email notifications for new registrations', 'gym-community-plugin' ); ?>
        </label>
        <?php
    }

    public function admin_email_callback() {
        $value = get_option( 'gym_community_admin_email', get_option( 'admin_email' ) );
        ?>
        <input type="email" name="gym_community_admin_email" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php _e( 'Email address to receive admin notifications', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function auto_approve_callback() {
        $value = get_option( 'gym_community_auto_approve_reviews', '0' );
        ?>
        <label>
            <input type="checkbox" name="gym_community_auto_approve_reviews" value="1" <?php checked( $value, '1' ); ?>>
            <?php _e( 'Automatically approve submitted reviews', 'gym-community-plugin' ); ?>
        </label>
        <p class="description"><?php _e( 'If disabled, reviews will need manual approval', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function registration_limit_callback() {
        $value = get_option( 'gym_community_registration_limit', '5' );
        ?>
        <input type="number" name="gym_community_registration_limit" value="<?php echo esc_attr( $value ); ?>" min="1" max="50" class="small-text">
        <p class="description"><?php _e( 'Maximum number of activities a user can register for', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'gym_community_messages', 'gym_community_message', __( 'Settings Saved', 'gym-community-plugin' ), 'updated' );
        }

        settings_errors( 'gym_community_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <form action="options.php" method="post">
                <?php
                settings_fields( 'gym_community_settings' );
                do_settings_sections( 'gym-community-settings' );
                submit_button( __( 'Save Settings', 'gym-community-plugin' ) );
                ?>
            </form>

            <hr>

            <h2><?php _e( 'Plugin Statistics', 'gym-community-plugin' ); ?></h2>
            <div class="gym-stats">
                <?php
                $activities_count = wp_count_posts( 'gym_activity' );
                $reviews_count = wp_count_posts( 'gym_review' );
                
                global $wpdb;
                $table_name = $wpdb->prefix . 'gym_registrations';
                $registrations_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'confirmed'" );
                ?>
                <div class="stat-box">
                    <h3><?php echo esc_html( $activities_count->publish ); ?></h3>
                    <p><?php _e( 'Active Activities', 'gym-community-plugin' ); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo esc_html( $reviews_count->publish ); ?></h3>
                    <p><?php _e( 'Published Reviews', 'gym-community-plugin' ); ?></p>
                </div>
                <div class="stat-box">
                    <h3><?php echo esc_html( $registrations_count ); ?></h3>
                    <p><?php _e( 'Total Registrations', 'gym-community-plugin' ); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_docs_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Gym Community Plugin Documentation', 'gym-community-plugin' ); ?></h1>

            <div class="gym-docs">
                <h2><?php _e( 'Available Shortcodes', 'gym-community-plugin' ); ?></h2>
                
                <div class="shortcode-doc">
                    <h3><code>[gym_activities]</code></h3>
                    <p><?php _e( 'Display a list of gym activities.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>limit</code> - <?php _e( 'Number of activities to show (default: 10)', 'gym-community-plugin' ); ?></li>
                        <li><code>type</code> - <?php _e( 'Filter by activity type slug', 'gym-community-plugin' ); ?></li>
                        <li><code>upcoming</code> - <?php _e( 'Show only upcoming activities (yes/no, default: yes)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Example:', 'gym-community-plugin' ); ?></strong> <code>[gym_activities limit="5" type="cardio"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[gym_schedule]</code></h3>
                    <p><?php _e( 'Display a weekly schedule of activities.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>days</code> - <?php _e( 'Number of days to show (default: 7)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Example:', 'gym-community-plugin' ); ?></strong> <code>[gym_schedule days="14"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[recent_reviews]</code></h3>
                    <p><?php _e( 'Display recent product/service reviews.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>limit</code> - <?php _e( 'Number of reviews to show (default: 5)', 'gym-community-plugin' ); ?></li>
                        <li><code>category</code> - <?php _e( 'Filter by review category slug', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Example:', 'gym-community-plugin' ); ?></strong> <code>[recent_reviews limit="3" category="supplements"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[gym_registration_form]</code></h3>
                    <p><?php _e( 'Display registration form for an activity.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>activity_id</code> - <?php _e( 'Activity ID (default: current post ID)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Example:', 'gym-community-plugin' ); ?></strong> <code>[gym_registration_form activity_id="123"]</code></p>
                </div>

                <hr>

                <h2><?php _e( 'Custom Post Types', 'gym-community-plugin' ); ?></h2>
                
                <h3><?php _e( 'Gym Activities', 'gym-community-plugin' ); ?></h3>
                <p><?php _e( 'Create and manage gym activities and classes with details like date, time, trainer, capacity, and more.', 'gym-community-plugin' ); ?></p>
                
                <h3><?php _e( 'Reviews', 'gym-community-plugin' ); ?></h3>
                <p><?php _e( 'Manage product and service reviews with ratings, pros/cons, and external links.', 'gym-community-plugin' ); ?></p>

                <hr>

                <h2><?php _e( 'Features', 'gym-community-plugin' ); ?></h2>
                <ul>
                    <li><?php _e( 'Activity registration system with capacity tracking', 'gym-community-plugin' ); ?></li>
                    <li><?php _e( 'Email notifications for registrations', 'gym-community-plugin' ); ?></li>
                    <li><?php _e( 'Star rating system for reviews', 'gym-community-plugin' ); ?></li>
                    <li><?php _e( 'Activity types and review categories taxonomies', 'gym-community-plugin' ); ?></li>
                    <li><?php _e( 'Responsive design and AJAX functionality', 'gym-community-plugin' ); ?></li>
                </ul>

                <hr>

                <h2><?php _e( 'Support', 'gym-community-plugin' ); ?></h2>
                <p><?php _e( 'For support and documentation, please refer to the README.md file in the plugin directory.', 'gym-community-plugin' ); ?></p>
            </div>
        </div>
        <?php
    }
}

new Gym_Admin();
