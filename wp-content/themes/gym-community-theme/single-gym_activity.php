<?php
/**
 * Single Template: Gym Activity
 *
 * Toont een enkele activiteit met alle details, metadata,
 * inschrijfformulier en gerelateerde activiteiten.
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        $activity_id = get_the_ID();
        $date       = get_post_meta( $activity_id, '_gym_activity_date', true );
        $time       = get_post_meta( $activity_id, '_gym_activity_time', true );
        $trainer    = get_post_meta( $activity_id, '_gym_activity_trainer', true );
        $capacity   = get_post_meta( $activity_id, '_gym_activity_capacity', true );
        $duration   = get_post_meta( $activity_id, '_gym_activity_duration', true );
        $location   = get_post_meta( $activity_id, '_gym_activity_location', true );
        $difficulty = get_post_meta( $activity_id, '_gym_activity_difficulty', true );

        $available_spots = class_exists( 'Gym_Registrations' ) ? Gym_Registrations::get_available_spots( $activity_id ) : -1;
        $is_full = ( $available_spots === 0 );
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                </div>
            <?php endif; ?>

            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                <div class="card-meta" style="margin-top: 15px; font-size: 0.95rem;">
                    <?php if ( $date ) : ?>
                        <span><?php echo esc_html( date_i18n( 'l j F Y', strtotime( $date ) ) ); ?></span>
                    <?php endif; ?>
                    <?php if ( $time ) : ?>
                        <span><?php echo esc_html( $time ); ?></span>
                    <?php endif; ?>
                    <?php if ( $trainer ) : ?>
                        <span><?php _e( 'Trainer:', 'gym-community' ); ?> <strong><?php echo esc_html( $trainer ); ?></strong></span>
                    <?php endif; ?>
                    <?php if ( $capacity ) : ?>
                        <span>
                            <?php if ( $is_full ) : ?>
                                <span style="color: var(--color-danger); font-weight: 700;"><?php _e( 'Volzet', 'gym-community' ); ?></span>
                            <?php else : ?>
                                <strong><?php echo esc_html( $available_spots ); ?></strong> <?php _e( 'plaatsen beschikbaar', 'gym-community' ); ?> (<?php echo esc_html( $capacity ); ?> <?php _e( 'totaal', 'gym-community' ); ?>)
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>

                <div class="features-grid" style="margin-top: 30px;">
                    <?php if ( $duration ) : ?>
                        <div class="feature-card" style="padding: 25px;">
                            <h4><?php _e( 'Duur', 'gym-community' ); ?></h4>
                            <p><?php echo esc_html( $duration ); ?> <?php _e( 'minuten', 'gym-community' ); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ( $location ) : ?>
                        <div class="feature-card" style="padding: 25px;">
                            <h4><?php _e( 'Locatie', 'gym-community' ); ?></h4>
                            <p><?php echo esc_html( $location ); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ( $difficulty ) : ?>
                        <div class="feature-card" style="padding: 25px;">
                            <h4><?php _e( 'Niveau', 'gym-community' ); ?></h4>
                            <p>
                                <span class="badge badge-<?php echo esc_attr( $difficulty ); ?>">
                                    <?php echo esc_html( ucfirst( str_replace( '-', ' ', $difficulty ) ) ); ?>
                                </span>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php
                    $terms = get_the_terms( $activity_id, 'activity_type' );
                    if ( $terms && ! is_wp_error( $terms ) ) :
                    ?>
                        <div class="feature-card" style="padding: 25px;">
                            <h4><?php _e( 'Type Activiteit', 'gym-community' ); ?></h4>
                            <p>
                                <?php
                                $types = array();
                                foreach ( $terms as $term ) {
                                    $types[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
                                }
                                echo implode( ', ', $types );
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( ! $is_full && function_exists( 'gym_community_plugin' ) ) : ?>
                <div class="mt-4" id="registration">
                    <?php echo do_shortcode( '[gym_registration_form]' ); ?>
                </div>
            <?php elseif ( $is_full ) : ?>
                <div class="cta-section mt-4" style="border-radius: var(--radius-lg);">
                    <h3 style="color: var(--color-white);"><?php _e( 'Deze activiteit is volzet', 'gym-community' ); ?></h3>
                    <p><?php _e( 'Bekijk onze andere beschikbare activiteiten.', 'gym-community' ); ?></p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn btn-secondary">
                        <?php _e( 'Alle Activiteiten', 'gym-community' ); ?>
                    </a>
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
    <?php endwhile; ?>
</div>

<?php
get_footer();
