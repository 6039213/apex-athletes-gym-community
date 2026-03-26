<?php
/**
 * The template for displaying search results
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__( 'Search Results for: %s', 'gym-community' ),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'search' );
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
