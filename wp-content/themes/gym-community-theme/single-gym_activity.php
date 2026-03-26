<?php
/**
 * The template for displaying single gym activities
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        $activity_id = get_the_ID();
        $date = get_post_meta( $activity_id, '_gym_activity_date', true );
        $time = get_post_meta( $activity_id, '_gym_activity_time', true );
        $trainer = get_post_meta( $activity_id, '_gym_activity_trainer', true );
        $capacity = get_post_meta( $activity_id, '_gym_activity_capacity', true );
        $duration = get_post_meta( $activity_id, '_gym_activity_duration', true );
        $location = get_post_meta( $activity_id, '_gym_activity_location', true );
        $difficulty = get_post_meta( $activity_id, '_gym_activity_difficulty', true );
        
        $available_spots = class_exists( 'Gym_Registrations' ) ? Gym_Registrations::get_available_spots( $activity_id ) : 0;
        $is_full = $available_spots === 0;
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                </div>
            <?php endif; ?>

            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                
                <div class="activity-quick-info">
                    <?php if ( $date && $time ) : ?>
                        <div class="quick-info-item">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <strong><?php echo date( 'l, F j, Y', strtotime( $date ) ); ?></strong> @ <?php echo esc_html( $time ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $trainer ) : ?>
                        <div class="quick-info-item">
                            <span class="dashicons dashicons-admin-users"></span>
                            Trainer: <strong><?php echo esc_html( $trainer ); ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $capacity ) : ?>
                        <div class="quick-info-item">
                            <span class="dashicons dashicons-groups"></span>
                            <?php if ( $is_full ) : ?>
                                <span style="color: #e74c3c; font-weight: 700;">FULLY BOOKED</span>
                            <?php else : ?>
                                <strong><?php echo esc_html( $available_spots ); ?></strong> spots available (of <?php echo esc_html( $capacity ); ?>)
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
                
                <div class="activity-details-grid">
                    <?php if ( $duration ) : ?>
                        <div class="detail-box">
                            <h3>Duration</h3>
                            <p><?php echo esc_html( $duration ); ?> minutes</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $location ) : ?>
                        <div class="detail-box">
                            <h3>Location</h3>
                            <p><?php echo esc_html( $location ); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $difficulty ) : ?>
                        <div class="detail-box">
                            <h3>Difficulty Level</h3>
                            <p class="difficulty-<?php echo esc_attr( $difficulty ); ?>">
                                <?php echo esc_html( ucfirst( str_replace( '-', ' ', $difficulty ) ) ); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    $terms = get_the_terms( $activity_id, 'activity_type' );
                    if ( $terms && ! is_wp_error( $terms ) ) :
                        ?>
                        <div class="detail-box">
                            <h3>Activity Type</h3>
                            <p>
                                <?php
                                $types = array();
                                foreach ( $terms as $term ) {
                                    $types[] = $term->name;
                                }
                                echo esc_html( implode( ', ', $types ) );
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( ! $is_full && function_exists( 'gym_community_plugin' ) ) : ?>
                <div class="activity-registration-section">
                    <?php echo do_shortcode( '[gym_registration_form]' ); ?>
                </div>
            <?php elseif ( $is_full ) : ?>
                <div class="activity-full-notice">
                    <p><strong>This activity is fully booked.</strong> Please check our other available activities.</p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn">View All Activities</a>
                </div>
            <?php endif; ?>

            <footer class="entry-footer">
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
            </footer>
        </article>

        <?php
    endwhile;
    ?>
</div>

<?php
get_footer();
