<?php
/**
 * Gym Reviews Custom Post Type
 *
 * @package Gym_Community_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Reviews {

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_filter( 'manage_gym_review_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_gym_review_posts_custom_column', array( $this, 'custom_column_content' ), 10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_gym_review', array( $this, 'save_meta_boxes' ) );
    }

    public static function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Reviews', 'Post Type General Name', 'gym-community-plugin' ),
            'singular_name'         => _x( 'Review', 'Post Type Singular Name', 'gym-community-plugin' ),
            'menu_name'             => __( 'Reviews', 'gym-community-plugin' ),
            'name_admin_bar'        => __( 'Review', 'gym-community-plugin' ),
            'archives'              => __( 'Review Archives', 'gym-community-plugin' ),
            'attributes'            => __( 'Review Attributes', 'gym-community-plugin' ),
            'parent_item_colon'     => __( 'Parent Review:', 'gym-community-plugin' ),
            'all_items'             => __( 'All Reviews', 'gym-community-plugin' ),
            'add_new_item'          => __( 'Add New Review', 'gym-community-plugin' ),
            'add_new'               => __( 'Add New', 'gym-community-plugin' ),
            'new_item'              => __( 'New Review', 'gym-community-plugin' ),
            'edit_item'             => __( 'Edit Review', 'gym-community-plugin' ),
            'update_item'           => __( 'Update Review', 'gym-community-plugin' ),
            'view_item'             => __( 'View Review', 'gym-community-plugin' ),
            'view_items'            => __( 'View Reviews', 'gym-community-plugin' ),
            'search_items'          => __( 'Search Review', 'gym-community-plugin' ),
            'not_found'             => __( 'Not found', 'gym-community-plugin' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'gym-community-plugin' ),
        );

        $args = array(
            'label'                 => __( 'Review', 'gym-community-plugin' ),
            'description'           => __( 'Product and service reviews', 'gym-community-plugin' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
            'taxonomies'            => array( 'review_category' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 6,
            'menu_icon'             => 'dashicons-star-filled',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );

        register_post_type( 'gym_review', $args );
    }

    public function register_taxonomies() {
        $labels = array(
            'name'              => _x( 'Review Categories', 'taxonomy general name', 'gym-community-plugin' ),
            'singular_name'     => _x( 'Review Category', 'taxonomy singular name', 'gym-community-plugin' ),
            'search_items'      => __( 'Search Review Categories', 'gym-community-plugin' ),
            'all_items'         => __( 'All Review Categories', 'gym-community-plugin' ),
            'parent_item'       => __( 'Parent Review Category', 'gym-community-plugin' ),
            'parent_item_colon' => __( 'Parent Review Category:', 'gym-community-plugin' ),
            'edit_item'         => __( 'Edit Review Category', 'gym-community-plugin' ),
            'update_item'       => __( 'Update Review Category', 'gym-community-plugin' ),
            'add_new_item'      => __( 'Add New Review Category', 'gym-community-plugin' ),
            'new_item_name'     => __( 'New Review Category Name', 'gym-community-plugin' ),
            'menu_name'         => __( 'Review Categories', 'gym-community-plugin' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'review-category' ),
            'show_in_rest'      => true,
        );

        register_taxonomy( 'review_category', array( 'gym_review' ), $args );
    }

    public function set_custom_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['rating'] = __( 'Rating', 'gym-community-plugin' );
        $new_columns['product'] = __( 'Product/Service', 'gym-community-plugin' );
        $new_columns['reviewer'] = __( 'Reviewer', 'gym-community-plugin' );
        $new_columns['review_category'] = __( 'Category', 'gym-community-plugin' );
        $new_columns['status'] = __( 'Status', 'gym-community-plugin' );
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    public function custom_column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'rating':
                $rating = get_post_meta( $post_id, '_gym_review_rating', true );
                if ( $rating ) {
                    echo $this->display_stars( $rating );
                    echo ' (' . esc_html( $rating ) . '/5)';
                } else {
                    echo '—';
                }
                break;

            case 'product':
                $product = get_post_meta( $post_id, '_gym_review_product', true );
                echo $product ? esc_html( $product ) : '—';
                break;

            case 'reviewer':
                $reviewer = get_post_meta( $post_id, '_gym_review_reviewer_name', true );
                echo $reviewer ? esc_html( $reviewer ) : get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
                break;

            case 'status':
                $status = get_post_status( $post_id );
                $status_labels = array(
                    'publish' => '<span style="color: green;">Approved</span>',
                    'pending' => '<span style="color: orange;">Pending</span>',
                    'draft'   => '<span style="color: gray;">Draft</span>',
                );
                echo isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : esc_html( $status );
                break;
        }
    }

    private function display_stars( $rating ) {
        $output = '';
        $full_stars = floor( $rating );
        $half_star = ( $rating - $full_stars ) >= 0.5;
        
        for ( $i = 0; $i < $full_stars; $i++ ) {
            $output .= '★';
        }
        
        if ( $half_star ) {
            $output .= '☆';
        }
        
        $empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );
        for ( $i = 0; $i < $empty_stars; $i++ ) {
            $output .= '☆';
        }
        
        return '<span style="color: #f39c12; font-size: 16px;">' . $output . '</span>';
    }

    public function add_meta_boxes() {
        add_meta_box(
            'gym_review_details',
            __( 'Review Details', 'gym-community-plugin' ),
            array( $this, 'render_meta_box' ),
            'gym_review',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'gym_review_meta_box', 'gym_review_meta_box_nonce' );

        $product = get_post_meta( $post->ID, '_gym_review_product', true );
        $rating = get_post_meta( $post->ID, '_gym_review_rating', true );
        $reviewer_name = get_post_meta( $post->ID, '_gym_review_reviewer_name', true );
        $reviewer_email = get_post_meta( $post->ID, '_gym_review_reviewer_email', true );
        $product_link = get_post_meta( $post->ID, '_gym_review_product_link', true );
        $verified = get_post_meta( $post->ID, '_gym_review_verified', true );
        $pros = get_post_meta( $post->ID, '_gym_review_pros', true );
        $cons = get_post_meta( $post->ID, '_gym_review_cons', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="gym_review_product"><?php _e( 'Product/Service Name', 'gym-community-plugin' ); ?></label></th>
                <td><input type="text" id="gym_review_product" name="gym_review_product" value="<?php echo esc_attr( $product ); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="gym_review_rating"><?php _e( 'Rating (1-5)', 'gym-community-plugin' ); ?></label></th>
                <td>
                    <select id="gym_review_rating" name="gym_review_rating" required>
                        <option value=""><?php _e( 'Select rating...', 'gym-community-plugin' ); ?></option>
                        <option value="1" <?php selected( $rating, '1' ); ?>>1 - Poor</option>
                        <option value="2" <?php selected( $rating, '2' ); ?>>2 - Fair</option>
                        <option value="3" <?php selected( $rating, '3' ); ?>>3 - Good</option>
                        <option value="4" <?php selected( $rating, '4' ); ?>>4 - Very Good</option>
                        <option value="5" <?php selected( $rating, '5' ); ?>>5 - Excellent</option>
                    </select>
                    <?php if ( $rating ) : ?>
                        <span style="margin-left: 10px;"><?php echo $this->display_stars( $rating ); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label for="gym_review_reviewer_name"><?php _e( 'Reviewer Name', 'gym-community-plugin' ); ?></label></th>
                <td><input type="text" id="gym_review_reviewer_name" name="gym_review_reviewer_name" value="<?php echo esc_attr( $reviewer_name ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_review_reviewer_email"><?php _e( 'Reviewer Email', 'gym-community-plugin' ); ?></label></th>
                <td><input type="email" id="gym_review_reviewer_email" name="gym_review_reviewer_email" value="<?php echo esc_attr( $reviewer_email ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="gym_review_product_link"><?php _e( 'Product Link (External)', 'gym-community-plugin' ); ?></label></th>
                <td><input type="url" id="gym_review_product_link" name="gym_review_product_link" value="<?php echo esc_attr( $product_link ); ?>" class="regular-text" placeholder="https://"></td>
            </tr>
            <tr>
                <th><label for="gym_review_pros"><?php _e( 'Pros', 'gym-community-plugin' ); ?></label></th>
                <td><textarea id="gym_review_pros" name="gym_review_pros" rows="3" class="large-text"><?php echo esc_textarea( $pros ); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="gym_review_cons"><?php _e( 'Cons', 'gym-community-plugin' ); ?></label></th>
                <td><textarea id="gym_review_cons" name="gym_review_cons" rows="3" class="large-text"><?php echo esc_textarea( $cons ); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="gym_review_verified"><?php _e( 'Verified Purchase', 'gym-community-plugin' ); ?></label></th>
                <td>
                    <input type="checkbox" id="gym_review_verified" name="gym_review_verified" value="1" <?php checked( $verified, '1' ); ?>>
                    <span class="description"><?php _e( 'Check if this is a verified purchase/experience', 'gym-community-plugin' ); ?></span>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['gym_review_meta_box_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['gym_review_meta_box_nonce'], 'gym_review_meta_box' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = array(
            'gym_review_product'        => 'sanitize_text_field',
            'gym_review_rating'         => 'sanitize_text_field',
            'gym_review_reviewer_name'  => 'sanitize_text_field',
            'gym_review_reviewer_email' => 'sanitize_email',
            'gym_review_product_link'   => 'esc_url_raw',
            'gym_review_pros'           => 'sanitize_textarea_field',
            'gym_review_cons'           => 'sanitize_textarea_field',
        );

        foreach ( $fields as $field => $sanitize_callback ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize_callback, $_POST[ $field ] ) );
            }
        }

        $verified = isset( $_POST['gym_review_verified'] ) ? '1' : '0';
        update_post_meta( $post_id, '_gym_review_verified', $verified );
    }

    public static function get_average_rating( $product_name = '' ) {
        $args = array(
            'post_type'      => 'gym_review',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );

        if ( $product_name ) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_gym_review_product',
                    'value'   => $product_name,
                    'compare' => '=',
                ),
            );
        }

        $reviews = get_posts( $args );
        
        if ( empty( $reviews ) ) {
            return 0;
        }

        $total_rating = 0;
        foreach ( $reviews as $review ) {
            $rating = get_post_meta( $review->ID, '_gym_review_rating', true );
            $total_rating += floatval( $rating );
        }

        return round( $total_rating / count( $reviews ), 1 );
    }
}

new Gym_Reviews();
