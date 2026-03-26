<?php
/**
 * Template part for displaying posts
 *
 * @package Gym_Community_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'gym-community-featured' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;
        ?>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php gym_community_posted_on(); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content();
            
            wp_link_pages( array(
                'before' => '<div class="page-links">' . __( 'Pages:', 'gym-community' ),
                'after'  => '</div>',
            ) );
        else :
            the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="more-link">
                <?php _e( 'Read More', 'gym-community' ); ?> &raquo;
            </a>
            <?php
        endif;
        ?>
    </div>

    <?php if ( is_singular() ) : ?>
        <footer class="entry-footer">
            <?php gym_community_entry_footer(); ?>
        </footer>
    <?php endif; ?>
</article>
