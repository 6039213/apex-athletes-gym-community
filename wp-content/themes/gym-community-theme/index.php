<?php
/**
 * The main template file
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php if ( have_posts() ) : ?>

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', get_post_type() );
        endwhile;

        gym_community_pagination();

    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>
</div>

<?php
if ( is_active_sidebar( 'sidebar-1' ) ) {
    get_sidebar();
}

get_footer();
