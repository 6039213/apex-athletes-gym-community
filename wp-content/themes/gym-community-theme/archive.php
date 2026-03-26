<?php
/**
 * The template for displaying archive pages
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <?php
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </header>

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
