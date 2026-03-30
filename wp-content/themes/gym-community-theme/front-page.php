<?php
/**
 * The front page template file
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <!-- Challenge 3b: Zichtbare Aanpassing -->
    <div class="welcome-banner" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; text-align: center; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h2 style="color: white; font-size: 2.5em; margin: 0 0 15px 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
            🏋️ Welkom bij Gym Community! 💪
        </h2>
        <p style="color: #f0f0f0; font-size: 1.2em; margin: 0; max-width: 800px; margin: 0 auto;">
            Jouw ultieme platform voor fitness activiteiten, reviews en community events. Start vandaag nog met je fitness journey!
        </p>
        <p style="color: #ffd700; font-size: 0.9em; margin-top: 15px; font-style: italic;">
            ✨ Challenge 3b - Docker Deployment Aanpassing ✨
        </p>
    </div>
    
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
