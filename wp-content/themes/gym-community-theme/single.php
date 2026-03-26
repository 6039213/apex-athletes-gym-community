<?php
/**
 * The template for displaying single posts
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content', get_post_type() );

        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        the_post_navigation( array(
            'prev_text' => '<span class="nav-subtitle">' . __( 'Previous:', 'gym-community' ) . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . __( 'Next:', 'gym-community' ) . '</span> <span class="nav-title">%title</span>',
        ) );

    endwhile;
    ?>
</div>

<?php
if ( is_active_sidebar( 'sidebar-1' ) ) {
    get_sidebar();
}

get_footer();
