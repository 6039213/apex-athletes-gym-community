<?php
/**
 * 404 Template - Apex Athletes
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();
?>

<div class="content-area">
    <section class="error-404 not-found">
        <h1>404</h1>
        <h2><?php _e( 'Pagina niet gevonden', 'gym-community' ); ?></h2>
        <p><?php _e( 'De pagina die je zoekt bestaat niet of is verplaatst. Gebruik de zoekfunctie of bekijk onze populaire pagina\'s.', 'gym-community' ); ?></p>

        <div class="mt-3 mb-3" style="max-width: 500px; margin-left: auto; margin-right: auto;">
            <?php get_search_form(); ?>
        </div>

        <div class="hero-buttons mt-3">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php _e( 'Terug naar Home', 'gym-community' ); ?></a>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn btn-outline"><?php _e( 'Activiteiten', 'gym-community' ); ?></a>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_review' ) ); ?>" class="btn btn-outline"><?php _e( 'Reviews', 'gym-community' ); ?></a>
        </div>
    </section>
</div>

<?php
get_footer();
