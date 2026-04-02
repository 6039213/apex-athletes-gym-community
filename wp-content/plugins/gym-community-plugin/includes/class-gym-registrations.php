<?php
/**
 * Gym Registrations Handler - Apex Athletes
 *
 * Beheert AJAX inschrijvingen voor activiteiten met capaciteitscontrole,
 * dubbele-inschrijving preventie, bevestigingsmails en admin overzicht.
 * Bevat action/filter hooks voor extensibiliteit.
 *
 * @package Gym_Community_Plugin
 * @since 2.0.0
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
            wp_send_json_error( array( 'message' => __( 'Vul alle verplichte velden in.', 'gym-community-plugin' ) ) );
        }

        if ( ! is_email( $user_email ) ) {
            wp_send_json_error( array( 'message' => __( 'Voer een geldig e-mailadres in.', 'gym-community-plugin' ) ) );
        }

        if ( ! $this->check_capacity( $activity_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Sorry, deze activiteit is volzet.', 'gym-community-plugin' ) ) );
        }

        if ( $this->is_already_registered( $activity_id, $user_email ) ) {
            wp_send_json_error( array( 'message' => __( 'Je bent al ingeschreven voor deze activiteit.', 'gym-community-plugin' ) ) );
        }

        /**
         * Hook: gym_community_before_registration
         * Vuurt af voor het verwerken van een inschrijving.
         *
         * @param int    $activity_id Het activiteit ID.
         * @param string $user_email  E-mailadres van de deelnemer.
         */
        do_action( 'gym_community_before_registration', $activity_id, $user_email );

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
            $registration_id = $wpdb->insert_id;

            $this->send_confirmation_email( $activity_id, $user_name, $user_email );

            /**
             * Hook: gym_community_after_registration
             * Vuurt af na een succesvolle inschrijving.
             *
             * @param int    $registration_id Het registratie ID.
             * @param int    $activity_id     Het activiteit ID.
             * @param string $user_name       Naam van de deelnemer.
             * @param string $user_email      E-mailadres van de deelnemer.
             */
            do_action( 'gym_community_after_registration', $registration_id, $activity_id, $user_name, $user_email );

            wp_send_json_success( array(
                'message' => __( 'Inschrijving gelukt! Controleer je e-mail voor de bevestiging.', 'gym-community-plugin' )
            ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Inschrijving mislukt. Probeer het opnieuw.', 'gym-community-plugin' ) ) );
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

        $subject = get_option(
            'gym_community_confirmation_email_subject',
            sprintf( __( 'Bevestiging inschrijving: %s', 'gym-community-plugin' ), $activity->post_title )
        );

        $message = sprintf( __( 'Hallo %s,', 'gym-community-plugin' ), $user_name ) . "\n\n";
        $message .= __( 'Je inschrijving is bevestigd!', 'gym-community-plugin' ) . "\n\n";
        $message .= __( 'Activiteit Details:', 'gym-community-plugin' ) . "\n";
        $message .= sprintf( __( 'Activiteit: %s', 'gym-community-plugin' ), $activity->post_title ) . "\n";

        if ( $activity_date ) {
            $message .= sprintf( __( 'Datum: %s', 'gym-community-plugin' ), date_i18n( 'l j F Y', strtotime( $activity_date ) ) ) . "\n";
        }

        if ( $activity_time ) {
            $message .= sprintf( __( 'Tijd: %s', 'gym-community-plugin' ), $activity_time ) . "\n";
        }

        if ( $trainer ) {
            $message .= sprintf( __( 'Trainer: %s', 'gym-community-plugin' ), $trainer ) . "\n";
        }

        if ( $location ) {
            $message .= sprintf( __( 'Locatie: %s', 'gym-community-plugin' ), $location ) . "\n";
        }

        $message .= "\n" . __( 'We kijken ernaar uit je te zien!', 'gym-community-plugin' ) . "\n\n";
        $message .= get_bloginfo( 'name' ) . "\n";
        $message .= home_url();

        /**
         * Filter: gym_community_confirmation_email
         * Filter het bevestigingsmail bericht.
         *
         * @param string $message     Het e-mailbericht.
         * @param int    $activity_id Het activiteit ID.
         * @param string $user_name   Naam van de deelnemer.
         */
        $message = apply_filters( 'gym_community_confirmation_email', $message, $activity_id, $user_name );

        wp_mail( $user_email, $subject, $message );

        // Stuur ook notificatie naar admin indien ingeschakeld
        if ( get_option( 'gym_community_email_notifications', '1' ) === '1' ) {
            $admin_email   = get_option( 'gym_community_admin_email', get_option( 'admin_email' ) );
            $admin_subject = sprintf( __( 'Nieuwe inschrijving: %s', 'gym-community-plugin' ), $activity->post_title );
            $admin_message = sprintf( __( 'Nieuwe inschrijving ontvangen voor %s', 'gym-community-plugin' ), $activity->post_title ) . "\n\n";
            $admin_message .= sprintf( __( 'Naam: %s', 'gym-community-plugin' ), $user_name ) . "\n";
            $admin_message .= sprintf( __( 'E-mail: %s', 'gym-community-plugin' ), $user_email ) . "\n";
            wp_mail( $admin_email, $admin_subject, $admin_message );
        }
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
            <h1><?php _e( 'Inschrijvingen', 'gym-community-plugin' ); ?></h1>

            <form method="get">
                <input type="hidden" name="post_type" value="gym_activity">
                <input type="hidden" name="page" value="gym-registrations">
                
                <select name="activity" onchange="this.form.submit()">
                    <option value="0"><?php _e( 'Alle Activiteiten', 'gym-community-plugin' ); ?></option>
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
