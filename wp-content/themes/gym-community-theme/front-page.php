<?php
/**
 * The front page template file
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                </div>
            <?php endif; ?>

            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php
    endwhile;
    ?>

    <?php
    $recent_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $recent_posts->have_posts() ) :
        ?>
        <section class="recent-posts mt-3">
            <h2><?php _e( 'Latest News', 'gym-community' ); ?></h2>
            <div class="posts-grid">
                <?php
                while ( $recent_posts->have_posts() ) :
                    $recent_posts->the_post();
                    ?>
                    <article class="post-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'gym-community-thumbnail' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="entry-meta">
                            <?php echo get_the_date(); ?>
                        </div>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="btn"><?php _e( 'Read More', 'gym-community' ); ?></a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>
</div>

<?php
get_footer();
