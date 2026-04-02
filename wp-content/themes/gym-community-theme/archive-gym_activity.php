<?php
/**
 * Archive Template: Gym Activities
 *
 * Toont alle activiteiten in een grid-layout met filters,
 * metadata (datum, tijd, trainer, niveau) en inschrijfknoppen.
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();
?>

<div class="archive-header">
    <h1><?php _e( 'Activiteiten & Trainingen', 'gym-community' ); ?></h1>
    <p><?php _e( 'Ontdek ons complete aanbod aan trainingen, lessen en evenementen.', 'gym-community' ); ?></p>
</div>

<?php if ( have_posts() ) : ?>

    <div class="posts-grid">
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

            $available = -1;
            if ( class_exists( 'Gym_Registrations' ) ) {
                $available = Gym_Registrations::get_available_spots( $activity_id );
            }
            $is_full = ( $available === 0 );
        ?>
            <article class="activity-card <?php echo $is_full ? 'activity-full' : ''; ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                    <div class="card-meta">
                        <?php if ( $date ) : ?>
                            <span><?php echo esc_html( date_i18n( 'j F Y', strtotime( $date ) ) ); ?></span>
                        <?php endif; ?>
                        <?php if ( $time ) : ?>
                            <span><?php echo esc_html( $time ); ?></span>
                        <?php endif; ?>
                        <?php if ( $trainer ) : ?>
                            <span><?php echo esc_html( $trainer ); ?></span>
                        <?php endif; ?>
                        <?php if ( $duration ) : ?>
                            <span><?php echo esc_html( $duration ); ?> <?php _e( 'min', 'gym-community' ); ?></span>
                        <?php endif; ?>
                        <?php if ( $location ) : ?>
                            <span><?php echo esc_html( $location ); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ( $difficulty ) : ?>
                        <span class="badge badge-<?php echo esc_attr( $difficulty ); ?>">
                            <?php echo esc_html( ucfirst( str_replace( '-', ' ', $difficulty ) ) ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( $capacity ) : ?>
                        <p class="mt-1" style="font-size: 0.85rem; color: var(--color-text-light);">
                            <?php if ( $is_full ) : ?>
                                <span style="color: var(--color-danger); font-weight: 600;"><?php _e( 'Volzet', 'gym-community' ); ?></span>
                            <?php else : ?>
                                <strong><?php _e( 'Plaatsen:', 'gym-community' ); ?></strong>
                                <?php echo esc_html( $available ); ?> / <?php echo esc_html( $capacity ); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php the_excerpt(); ?>

                    <div class="card-actions">
                        <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Details', 'gym-community' ); ?></a>
                        <?php if ( ! $is_full ) : ?>
                            <a href="<?php the_permalink(); ?>#registration" class="btn btn-small btn-outline"><?php _e( 'Inschrijven', 'gym-community' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

    <?php gym_community_pagination(); ?>

<?php else : ?>

    <div class="content-area text-center">
        <h2><?php _e( 'Geen activiteiten gevonden', 'gym-community' ); ?></h2>
        <p><?php _e( 'Er zijn momenteel geen activiteiten gepland. Kom later terug!', 'gym-community' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn"><?php _e( 'Terug naar Home', 'gym-community' ); ?></a>
    </div>

<?php endif; ?>

<?php
get_footer();
