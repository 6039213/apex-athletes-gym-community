<?php
/**
 * Gym Activities Custom Post Type
 *
 * @package Gym_Community_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Activities {

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_filter( 'manage_gym_activity_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_gym_activity_posts_custom_column', array( $this, 'custom_column_content' ), 10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_gym_activity', array( $this, 'save_meta_boxes' ) );
    }

    public static function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Gym Activities', 'Post Type General Name', 'gym-community-plugin' ),
            'singular_name'         => _x( 'Gym Activity', 'Post Type Singular Name', 'gym-community-plugin' ),
            'menu_name'             => __( 'Gym Activities', 'gym-community-plugin' ),
            'name_admin_bar'        => __( 'Gym Activity', 'gym-community-plugin' ),
            'archives'              => __( 'Activity Archives', 'gym-community-plugin' ),
            'attributes'            => __( 'Activity Attributes', 'gym-community-plugin' ),
            'parent_item_colon'     => __( 'Parent Activity:', 'gym-community-plugin' ),
            'all_items'             => __( 'All Activities', 'gym-community-plugin' ),
            'add_new_item'          => __( 'Add New Activity', 'gym-community-plugin' ),
            'add_new'               => __( 'Add New', 'gym-community-plugin' ),
            'new_item'              => __( 'New Activity', 'gym-community-plugin' ),
            'edit_item'             => __( 'Edit Activity', 'gym-community-plugin' ),
            'update_item'           => __( 'Update Activity', 'gym-community-plugin' ),
            'view_item'             => __( 'View Activity', 'gym-community-plugin' ),
            'view_items'            => __( 'View Activities', 'gym-community-plugin' ),
            'search_items'          => __( 'Search Activity', 'gym-community-plugin' ),
            'not_found'             => __( 'Not found', 'gym-community-plugin' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'gym-community-plugin' ),
        );

        $args = array(
            'label'                 => __( 'Gym Activity', 'gym-community-plugin' ),
            'description'           => __( 'Gym activities and classes', 'gym-community-plugin' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
            'taxonomies'            => array( 'activity_type' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-heart',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );

        register_post_type( 'gym_activity', $args );
    }

    public function register_taxonomies() {
        $labels = array(
            'name'              => _x( 'Activity Types', 'taxonomy general name', 'gym-community-plugin' ),
            'singular_name'     => _x( 'Activity Type', 'taxonomy singular name', 'gym-community-plugin' ),
            'search_items'      => __( 'Search Activity Types', 'gym-community-plugin' ),
            'all_items'         => __( 'All Activity Types', 'gym-community-plugin' ),
            'parent_item'       => __( 'Parent Activity Type', 'gym-community-plugin' ),
            'parent_item_colon' => __( 'Parent Activity Type:', 'gym-community-plugin' ),
            'edit_item'         => __( 'Edit Activity Type', 'gym-community-plugin' ),
            'update_item'       => __( 'Update Activity Type', 'gym-community-plugin' ),
            'add_new_item'      => __( 'Add New Activity Type', 'gym-community-plugin' ),
            'new_item_name'     => __( 'New Activity Type Name', 'gym-community-plugin' ),
            'menu_name'         => __( 'Activity Types', 'gym-community-plugin' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'activity-type' ),
            'show_in_rest'      => true,
        );

        register_taxonomy( 'activity_type', array( 'gym_activity' ), $args );
    }

    public function set_custom_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['activity_date'] = __( 'Date & Time', 'gym-community-plugin' );
        $new_columns['trainer'] = __( 'Trainer', 'gym-community-plugin' );
        $new_columns['capacity'] = __( 'Capacity', 'gym-community-plugin' );
        $new_columns['registrations'] = __( 'Registrations', 'gym-community-plugin' );
        $new_columns['activity_type'] = __( 'Type', 'gym-community-plugin' );
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    public function custom_column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'activity_date':
                $date = get_post_meta( $post_id, '_gym_activity_date', true );
                $time = get_post_meta( $post_id, '_gym_activity_time', true );
                if ( $date ) {
                    echo esc_html( date( 'd-m-Y', strtotime( $date ) ) );
                    if ( $time ) {
                        echo ' @ ' . esc_html( $time );
                    }
                } else {
                    echo '—';
                }
                break;

            case 'trainer':
                $trainer = get_post_meta( $post_id, '_gym_activity_trainer', true );
                echo $trainer ? esc_html( $trainer ) : '—';
                break;

            case 'capacity':
                $capacity = get_post_meta( $post_id, '_gym_activity_capacity', true );
                echo $capacity ? esc_html( $capacity ) : '—';
                break;

            case 'registrations':
                global $wpdb;
                $table_name = $wpdb->prefix . 'gym_registrations';
                $count = $wpdb->get_var( $wpdb->prepare( 
                    "SELECT COUNT(*) FROM $table_name WHERE activity_id = %d AND status = 'confirmed'", 
                    $post_id 
                ) );
                $capacity = get_post_meta( $post_id, '_gym_activity_capacity', true );
                
                if ( $capacity ) {
                    echo esc_html( $count . ' / ' . $capacity );
                    if ( $count >= $capacity ) {
                        echo ' <span style="color: red;">(Full)</span>';
                    }
                } else {
                    echo esc_html( $count );
                }
                break;
        }
    }

    public function add_meta_boxes() {
        add_meta_box(
            'gym_activity_details',
            __( 'Activity Details', 'gym-community-plugin' ),
            array( $this, 'render_meta_box' ),
            'gym_activity',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'gym_activity_meta_box', 'gym_activity_meta_box_nonce' );

        $date = get_post_meta( $post->ID, '_gym_activity_date', true );
        $time = get_post_meta( $post->ID, '_gym_activity_time', true );
        $trainer = get_post_meta( $post->ID, '_gym_activity_trainer', true );
        $capacity = get_post_meta( $post->ID, '_gym_activity_capacity', true );
        $duration = get_post_meta( $post->ID, '_gym_activity_duration', true );
        $location = get_post_meta( $post->ID, '_gym_activity_location', true );
        $difficulty = get_post_meta( $post->ID, '_gym_activity_difficulty', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="gym_activity_date"><?php _e( 'Date', 'gym-community-plugin' ); ?></label></th>
                <td><input type="date" id="gym_activity_date" name="gym_activity_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_time"><?php _e( 'Time', 'gym-community-plugin' ); ?></label></th>
                <td><input type="time" id="gym_activity_time" name="gym_activity_time" value="<?php echo esc_attr( $time ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_trainer"><?php _e( 'Trainer', 'gym-community-plugin' ); ?></label></th>
                <td><input type="text" id="gym_activity_trainer" name="gym_activity_trainer" value="<?php echo esc_attr( $trainer ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_capacity"><?php _e( 'Max Capacity', 'gym-community-plugin' ); ?></label></th>
                <td><input type="number" id="gym_activity_capacity" name="gym_activity_capacity" value="<?php echo esc_attr( $capacity ); ?>" min="1" class="small-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_duration"><?php _e( 'Duration (minutes)', 'gym-community-plugin' ); ?></label></th>
                <td><input type="number" id="gym_activity_duration" name="gym_activity_duration" value="<?php echo esc_attr( $duration ); ?>" min="1" class="small-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_location"><?php _e( 'Location', 'gym-community-plugin' ); ?></label></th>
                <td><input type="text" id="gym_activity_location" name="gym_activity_location" value="<?php echo esc_attr( $location ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_activity_difficulty"><?php _e( 'Difficulty Level', 'gym-community-plugin' ); ?></label></th>
                <td>
                    <select id="gym_activity_difficulty" name="gym_activity_difficulty">
                        <option value=""><?php _e( 'Select...', 'gym-community-plugin' ); ?></option>
                        <option value="beginner" <?php selected( $difficulty, 'beginner' ); ?>><?php _e( 'Beginner', 'gym-community-plugin' ); ?></option>
                        <option value="intermediate" <?php selected( $difficulty, 'intermediate' ); ?>><?php _e( 'Intermediate', 'gym-community-plugin' ); ?></option>
                        <option value="advanced" <?php selected( $difficulty, 'advanced' ); ?>><?php _e( 'Advanced', 'gym-community-plugin' ); ?></option>
                        <option value="all-levels" <?php selected( $difficulty, 'all-levels' ); ?>><?php _e( 'All Levels', 'gym-community-plugin' ); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['gym_activity_meta_box_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['gym_activity_meta_box_nonce'], 'gym_activity_meta_box' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = array(
            'gym_activity_date',
            'gym_activity_time',
            'gym_activity_trainer',
            'gym_activity_capacity',
            'gym_activity_duration',
            'gym_activity_location',
            'gym_activity_difficulty',
        );

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }
    }
}

new Gym_Activities();
