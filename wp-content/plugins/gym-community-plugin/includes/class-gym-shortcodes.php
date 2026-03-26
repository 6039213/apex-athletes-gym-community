<?php
/**
 * Gym Shortcodes
 *
 * @package Gym_Community_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Shortcodes {

    public function __construct() {
        add_shortcode( 'gym_activities', array( $this, 'activities_shortcode' ) );
        add_shortcode( 'gym_schedule', array( $this, 'schedule_shortcode' ) );
        add_shortcode( 'recent_reviews', array( $this, 'recent_reviews_shortcode' ) );
        add_shortcode( 'product_reviews', array( $this, 'product_reviews_shortcode' ) );
        add_shortcode( 'gym_registration_form', array( $this, 'registration_form_shortcode' ) );
    }

    public function activities_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'limit'    => 10,
            'type'     => '',
            'upcoming' => 'yes',
        ), $atts );

        $args = array(
            'post_type'      => 'gym_activity',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
        );

        if ( $atts['type'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'activity_type',
                    'field'    => 'slug',
                    'terms'    => $atts['type'],
                ),
            );
        }

        if ( $atts['upcoming'] === 'yes' ) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_gym_activity_date',
                    'value'   => date( 'Y-m-d' ),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
            );
            $args['meta_key'] = '_gym_activity_date';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
        }

        $activities = new WP_Query( $args );

        ob_start();

        if ( $activities->have_posts() ) {
            echo '<div class="gym-activities-list">';
            
            while ( $activities->have_posts() ) {
                $activities->the_post();
                $activity_id = get_the_ID();
                $date = get_post_meta( $activity_id, '_gym_activity_date', true );
                $time = get_post_meta( $activity_id, '_gym_activity_time', true );
                $trainer = get_post_meta( $activity_id, '_gym_activity_trainer', true );
                $capacity = get_post_meta( $activity_id, '_gym_activity_capacity', true );
                $duration = get_post_meta( $activity_id, '_gym_activity_duration', true );
                $location = get_post_meta( $activity_id, '_gym_activity_location', true );
                $difficulty = get_post_meta( $activity_id, '_gym_activity_difficulty', true );
                
                $available_spots = Gym_Registrations::get_available_spots( $activity_id );
                $is_full = $available_spots === 0;
                ?>
                <div class="gym-activity-card <?php echo $is_full ? 'activity-full' : ''; ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="activity-thumbnail">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="activity-content">
                        <h3><?php the_title(); ?></h3>
                        
                        <div class="activity-meta">
                            <?php if ( $date ) : ?>
                                <p><strong><?php _e( 'Date:', 'gym-community-plugin' ); ?></strong> <?php echo date( 'd-m-Y', strtotime( $date ) ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( $time ) : ?>
                                <p><strong><?php _e( 'Time:', 'gym-community-plugin' ); ?></strong> <?php echo esc_html( $time ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( $trainer ) : ?>
                                <p><strong><?php _e( 'Trainer:', 'gym-community-plugin' ); ?></strong> <?php echo esc_html( $trainer ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( $duration ) : ?>
                                <p><strong><?php _e( 'Duration:', 'gym-community-plugin' ); ?></strong> <?php echo esc_html( $duration ); ?> <?php _e( 'minutes', 'gym-community-plugin' ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( $location ) : ?>
                                <p><strong><?php _e( 'Location:', 'gym-community-plugin' ); ?></strong> <?php echo esc_html( $location ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( $difficulty ) : ?>
                                <p><strong><?php _e( 'Level:', 'gym-community-plugin' ); ?></strong> <span class="difficulty-<?php echo esc_attr( $difficulty ); ?>"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $difficulty ) ) ); ?></span></p>
                            <?php endif; ?>
                            
                            <?php if ( $capacity ) : ?>
                                <p><strong><?php _e( 'Available Spots:', 'gym-community-plugin' ); ?></strong> 
                                    <?php if ( $is_full ) : ?>
                                        <span class="spots-full"><?php _e( 'FULL', 'gym-community-plugin' ); ?></span>
                                    <?php else : ?>
                                        <span class="spots-available"><?php echo esc_html( $available_spots ); ?> / <?php echo esc_html( $capacity ); ?></span>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="activity-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="activity-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'View Details', 'gym-community-plugin' ); ?></a>
                            <?php if ( ! $is_full ) : ?>
                                <a href="<?php the_permalink(); ?>#registration" class="btn btn-secondary"><?php _e( 'Register Now', 'gym-community-plugin' ); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __( 'No activities found.', 'gym-community-plugin' ) . '</p>';
        }

        return ob_get_clean();
    }

    public function schedule_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'days' => 7,
        ), $atts );

        $start_date = date( 'Y-m-d' );
        $end_date = date( 'Y-m-d', strtotime( '+' . intval( $atts['days'] ) . ' days' ) );

        $args = array(
            'post_type'      => 'gym_activity',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => '_gym_activity_date',
                    'value'   => array( $start_date, $end_date ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
            ),
            'meta_key'       => '_gym_activity_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
        );

        $activities = new WP_Query( $args );

        ob_start();

        if ( $activities->have_posts() ) {
            echo '<div class="gym-schedule">';
            echo '<h3>' . __( 'Weekly Schedule', 'gym-community-plugin' ) . '</h3>';
            
            $current_date = '';
            
            while ( $activities->have_posts() ) {
                $activities->the_post();
                $activity_id = get_the_ID();
                $date = get_post_meta( $activity_id, '_gym_activity_date', true );
                $time = get_post_meta( $activity_id, '_gym_activity_time', true );
                $trainer = get_post_meta( $activity_id, '_gym_activity_trainer', true );
                
                if ( $date !== $current_date ) {
                    if ( $current_date !== '' ) {
                        echo '</div>';
                    }
                    $current_date = $date;
                    echo '<div class="schedule-day">';
                    echo '<h4>' . date( 'l, F j, Y', strtotime( $date ) ) . '</h4>';
                    echo '<div class="schedule-activities">';
                }
                ?>
                <div class="schedule-item">
                    <span class="schedule-time"><?php echo esc_html( $time ); ?></span>
                    <span class="schedule-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                    <?php if ( $trainer ) : ?>
                        <span class="schedule-trainer"><?php echo esc_html( $trainer ); ?></span>
                    <?php endif; ?>
                </div>
                <?php
            }
            
            echo '</div></div></div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __( 'No scheduled activities.', 'gym-community-plugin' ) . '</p>';
        }

        return ob_get_clean();
    }

    public function recent_reviews_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'limit'    => 5,
            'category' => '',
        ), $atts );

        $args = array(
            'post_type'      => 'gym_review',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( $atts['category'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'review_category',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ),
            );
        }

        $reviews = new WP_Query( $args );

        ob_start();

        if ( $reviews->have_posts() ) {
            echo '<div class="gym-reviews-list">';
            
            while ( $reviews->have_posts() ) {
                $reviews->the_post();
                $review_id = get_the_ID();
                $product = get_post_meta( $review_id, '_gym_review_product', true );
                $rating = get_post_meta( $review_id, '_gym_review_rating', true );
                $reviewer = get_post_meta( $review_id, '_gym_review_reviewer_name', true );
                $verified = get_post_meta( $review_id, '_gym_review_verified', true );
                $product_link = get_post_meta( $review_id, '_gym_review_product_link', true );
                ?>
                <div class="gym-review-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="review-thumbnail">
                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="review-content">
                        <div class="review-header">
                            <h4><?php echo esc_html( $product ); ?></h4>
                            <?php if ( $rating ) : ?>
                                <div class="review-rating">
                                    <?php echo $this->display_stars( $rating ); ?>
                                    <span class="rating-number"><?php echo esc_html( $rating ); ?>/5</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h5><?php the_title(); ?></h5>
                        
                        <div class="review-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="review-meta">
                            <?php if ( $reviewer ) : ?>
                                <span class="reviewer-name"><?php _e( 'By', 'gym-community-plugin' ); ?> <?php echo esc_html( $reviewer ); ?></span>
                            <?php endif; ?>
                            
                            <?php if ( $verified ) : ?>
                                <span class="verified-badge">✓ <?php _e( 'Verified', 'gym-community-plugin' ); ?></span>
                            <?php endif; ?>
                            
                            <span class="review-date"><?php echo get_the_date(); ?></span>
                        </div>
                        
                        <div class="review-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Read Full Review', 'gym-community-plugin' ); ?></a>
                            <?php if ( $product_link ) : ?>
                                <a href="<?php echo esc_url( $product_link ); ?>" target="_blank" rel="noopener" class="btn btn-small btn-external"><?php _e( 'Buy Now', 'gym-community-plugin' ); ?> →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __( 'No reviews found.', 'gym-community-plugin' ) . '</p>';
        }

        return ob_get_clean();
    }

    public function product_reviews_shortcode( $atts ) {
        return $this->recent_reviews_shortcode( $atts );
    }

    public function registration_form_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'activity_id' => get_the_ID(),
        ), $atts );

        $activity_id = intval( $atts['activity_id'] );
        
        if ( ! $activity_id || get_post_type( $activity_id ) !== 'gym_activity' ) {
            return '<p>' . __( 'Invalid activity.', 'gym-community-plugin' ) . '</p>';
        }

        $available_spots = Gym_Registrations::get_available_spots( $activity_id );
        $is_full = $available_spots === 0;

        ob_start();
        ?>
        <div class="gym-registration-form" id="registration">
            <h3><?php _e( 'Register for this Activity', 'gym-community-plugin' ); ?></h3>
            
            <?php if ( $is_full ) : ?>
                <div class="registration-full">
                    <p><?php _e( 'Sorry, this activity is fully booked.', 'gym-community-plugin' ); ?></p>
                </div>
            <?php else : ?>
                <form id="gym-registration-form-<?php echo esc_attr( $activity_id ); ?>" class="gym-form">
                    <input type="hidden" name="activity_id" value="<?php echo esc_attr( $activity_id ); ?>">
                    
                    <div class="form-group">
                        <label for="user_name"><?php _e( 'Full Name', 'gym-community-plugin' ); ?> <span class="required">*</span></label>
                        <input type="text" id="user_name" name="user_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_email"><?php _e( 'Email Address', 'gym-community-plugin' ); ?> <span class="required">*</span></label>
                        <input type="email" id="user_email" name="user_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_phone"><?php _e( 'Phone Number', 'gym-community-plugin' ); ?></label>
                        <input type="tel" id="user_phone" name="user_phone">
                    </div>
                    
                    <div class="form-message"></div>
                    
                    <button type="submit" class="btn btn-primary"><?php _e( 'Register Now', 'gym-community-plugin' ); ?></button>
                </form>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function display_stars( $rating ) {
        $output = '';
        $full_stars = floor( $rating );
        $half_star = ( $rating - $full_stars ) >= 0.5;
        
        for ( $i = 0; $i < $full_stars; $i++ ) {
            $output .= '<span class="star star-full">★</span>';
        }
        
        if ( $half_star ) {
            $output .= '<span class="star star-half">★</span>';
        }
        
        $empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );
        for ( $i = 0; $i < $empty_stars; $i++ ) {
            $output .= '<span class="star star-empty">☆</span>';
        }
        
        return '<span class="stars">' . $output . '</span>';
    }
}

new Gym_Shortcodes();
