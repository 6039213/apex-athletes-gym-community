<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php _e( '404 - Page Not Found', 'gym-community' ); ?></h1>
        </header>

        <div class="page-content">
            <p><?php _e( 'Oops! The page you are looking for doesn\'t exist. It might have been moved or deleted.', 'gym-community' ); ?></p>

            <h2><?php _e( 'Try searching for what you need:', 'gym-community' ); ?></h2>
            <?php get_search_form(); ?>

            <h2><?php _e( 'Or browse our popular pages:', 'gym-community' ); ?></h2>
            <ul>
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'gym-community' ); ?></a></li>
                <?php
                wp_list_pages( array(
                    'title_li' => '',
                    'depth'    => 1,
                    'number'   => 5,
                ) );
                ?>
            </ul>
        </div>
    </section>
</div>

<?php
get_footer();
