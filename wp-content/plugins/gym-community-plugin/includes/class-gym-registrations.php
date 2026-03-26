<?php
/**
 * Gym Registrations Handler
 *
 * @package Gym_Community_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Registrations {

    public function __construct() {
        add_action( 'wp_ajax_gym_register_activity', array( $this, 'handle_registration' ) );
        add_action( 'wp_ajax_nopriv_gym_register_activity', array( $this, 'handle_registration' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    public function handle_registration() {
        check_ajax_referer( 'gym_community_nonce', 'nonce' );

        $activity_id = isset( $_POST['activity_id'] ) ? intval( $_POST['activity_id'] ) : 0;
        $user_name = isset( $_POST['user_name'] ) ? sanitize_text_field( $_POST['user_name'] ) : '';
        $user_email = isset( $_POST['user_email'] ) ? sanitize_email( $_POST['user_email'] ) : '';
        $user_phone = isset( $_POST['user_phone'] ) ? sanitize_text_field( $_POST['user_phone'] ) : '';

        if ( ! $activity_id || ! $user_name || ! $user_email ) {
            wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'gym-community-plugin' ) ) );
        }

        if ( ! is_email( $user_email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'gym-community-plugin' ) ) );
        }

        if ( ! $this->check_capacity( $activity_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Sorry, this activity is fully booked.', 'gym-community-plugin' ) ) );
        }

        if ( $this->is_already_registered( $activity_id, $user_email ) ) {
            wp_send_json_error( array( 'message' => __( 'You are already registered for this activity.', 'gym-community-plugin' ) ) );
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'gym_registrations';

        $result = $wpdb->insert(
            $table_name,
            array(
                'activity_id'       => $activity_id,
                'user_name'         => $user_name,
                'user_email'        => $user_email,
                'user_phone'        => $user_phone,
                'registration_date' => current_time( 'mysql' ),
                'status'            => 'confirmed',
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s' )
        );

        if ( $result ) {
            $this->send_confirmation_email( $activity_id, $user_name, $user_email );
            
            wp_send_json_success( array( 
                'message' => __( 'Registration successful! Check your email for confirmation.', 'gym-community-plugin' ) 
            ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Registration failed. Please try again.', 'gym-community-plugin' ) ) );
        }
    }

    private function check_capacity( $activity_id ) {
        $capacity = get_post_meta( $activity_id, '_gym_activity_capacity', true );
        
        if ( ! $capacity ) {
            return true;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'gym_registrations';
        
        $count = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE activity_id = %d AND status = 'confirmed'",
            $activity_id
        ) );

        return $count < $capacity;
    }

    private function is_already_registered( $activity_id, $user_email ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gym_registrations';
        
        $count = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE activity_id = %d AND user_email = %s AND status = 'confirmed'",
            $activity_id,
            $user_email
        ) );

        return $count > 0;
    }

    private function send_confirmation_email( $activity_id, $user_name, $user_email ) {
        $activity = get_post( $activity_id );
        $activity_date = get_post_meta( $activity_id, '_gym_activity_date', true );
        $activity_time = get_post_meta( $activity_id, '_gym_activity_time', true );
        $trainer = get_post_meta( $activity_id, '_gym_activity_trainer', true );
        $location = get_post_meta( $activity_id, '_gym_activity_location', true );

        $subject = sprintf( __( 'Registration Confirmation: %s', 'gym-community-plugin' ), $activity->post_title );

        $message = sprintf( __( 'Hi %s,', 'gym-community-plugin' ), $user_name ) . "\n\n";
        $message .= __( 'Your registration has been confirmed!', 'gym-community-plugin' ) . "\n\n";
        $message .= __( 'Activity Details:', 'gym-community-plugin' ) . "\n";
        $message .= sprintf( __( 'Activity: %s', 'gym-community-plugin' ), $activity->post_title ) . "\n";
        
        if ( $activity_date ) {
            $message .= sprintf( __( 'Date: %s', 'gym-community-plugin' ), date( 'd-m-Y', strtotime( $activity_date ) ) ) . "\n";
        }
        
        if ( $activity_time ) {
            $message .= sprintf( __( 'Time: %s', 'gym-community-plugin' ), $activity_time ) . "\n";
        }
        
        if ( $trainer ) {
            $message .= sprintf( __( 'Trainer: %s', 'gym-community-plugin' ), $trainer ) . "\n";
        }
        
        if ( $location ) {
            $message .= sprintf( __( 'Location: %s', 'gym-community-plugin' ), $location ) . "\n";
        }

        $message .= "\n" . __( 'We look forward to seeing you!', 'gym-community-plugin' ) . "\n\n";
        $message .= get_bloginfo( 'name' );

        wp_mail( $user_email, $subject, $message );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=gym_activity',
            __( 'Registrations', 'gym-community-plugin' ),
            __( 'Registrations', 'gym-community-plugin' ),
            'manage_options',
            'gym-registrations',
            array( $this, 'render_registrations_page' )
        );
    }

    public function render_registrations_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gym_registrations';

        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) ) {
            $wpdb->delete( $table_name, array( 'id' => intval( $_GET['id'] ) ), array( '%d' ) );
            echo '<div class="notice notice-success"><p>' . __( 'Registration deleted.', 'gym-community-plugin' ) . '</p></div>';
        }

        $activity_filter = isset( $_GET['activity'] ) ? intval( $_GET['activity'] ) : 0;

        $query = "SELECT * FROM $table_name";
        if ( $activity_filter ) {
            $query .= $wpdb->prepare( " WHERE activity_id = %d", $activity_filter );
        }
        $query .= " ORDER BY registration_date DESC";

        $registrations = $wpdb->get_results( $query );

        $activities = get_posts( array(
            'post_type'      => 'gym_activity',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        ?>
        <div class="wrap">
            <h1><?php _e( 'Activity Registrations', 'gym-community-plugin' ); ?></h1>

            <form method="get">
                <input type="hidden" name="post_type" value="gym_activity">
                <input type="hidden" name="page" value="gym-registrations">
                
                <select name="activity" onchange="this.form.submit()">
                    <option value="0"><?php _e( 'All Activities', 'gym-community-plugin' ); ?></option>
                    <?php foreach ( $activities as $activity ) : ?>
                        <option value="<?php echo esc_attr( $activity->ID ); ?>" <?php selected( $activity_filter, $activity->ID ); ?>>
                            <?php echo esc_html( $activity->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e( 'ID', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Activity', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Name', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Email', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Phone', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Registration Date', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Status', 'gym-community-plugin' ); ?></th>
                        <th><?php _e( 'Actions', 'gym-community-plugin' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $registrations ) ) : ?>
                        <tr>
                            <td colspan="8"><?php _e( 'No registrations found.', 'gym-community-plugin' ); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $registrations as $registration ) : ?>
                            <?php $activity = get_post( $registration->activity_id ); ?>
                            <tr>
                                <td><?php echo esc_html( $registration->id ); ?></td>
                                <td>
                                    <?php if ( $activity ) : ?>
                                        <a href="<?php echo get_edit_post_link( $activity->ID ); ?>">
                                            <?php echo esc_html( $activity->post_title ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php _e( 'Activity deleted', 'gym-community-plugin' ); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html( $registration->user_name ); ?></td>
                                <td><a href="mailto:<?php echo esc_attr( $registration->user_email ); ?>"><?php echo esc_html( $registration->user_email ); ?></a></td>
                                <td><?php echo esc_html( $registration->user_phone ); ?></td>
                                <td><?php echo esc_html( date( 'd-m-Y H:i', strtotime( $registration->registration_date ) ) ); ?></td>
                                <td>
                                    <?php
                                    $status_colors = array(
                                        'confirmed' => 'green',
                                        'pending'   => 'orange',
                                        'cancelled' => 'red',
                                    );
                                    $color = isset( $status_colors[ $registration->status ] ) ? $status_colors[ $registration->status ] : 'gray';
                                    ?>
                                    <span style="color: <?php echo esc_attr( $color ); ?>;">
                                        <?php echo esc_html( ucfirst( $registration->status ) ); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?post_type=gym_activity&page=gym-registrations&action=delete&id=<?php echo esc_attr( $registration->id ); ?>" 
                                       onclick="return confirm('<?php _e( 'Are you sure you want to delete this registration?', 'gym-community-plugin' ); ?>')">
                                        <?php _e( 'Delete', 'gym-community-plugin' ); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public static function get_registration_count( $activity_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gym_registrations';
        
        return $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE activity_id = %d AND status = 'confirmed'",
            $activity_id
        ) );
    }

    public static function get_available_spots( $activity_id ) {
        $capacity = get_post_meta( $activity_id, '_gym_activity_capacity', true );
        
        if ( ! $capacity ) {
            return -1;
        }

        $registered = self::get_registration_count( $activity_id );
        return max( 0, $capacity - $registered );
    }
}

new Gym_Registrations();
