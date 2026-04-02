<?php
/**
 * Archive Template: Gym Reviews
 *
 * Toont alle reviews in een grid-layout met sterren-ratings,
 * product informatie en externe links.
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();
?>

<div class="archive-header">
    <h1><?php _e( 'Product & Dienst Reviews', 'gym-community' ); ?></h1>
    <p><?php _e( 'Eerlijke reviews door de Apex Athletes community over fitnessproducten, supplementen en meer.', 'gym-community' ); ?></p>
</div>

<?php if ( have_posts() ) : ?>

    <div class="posts-grid">
        <?php
        while ( have_posts() ) :
            the_post();
            $review_id    = get_the_ID();
            $product      = get_post_meta( $review_id, '_gym_review_product', true );
            $rating       = get_post_meta( $review_id, '_gym_review_rating', true );
            $reviewer     = get_post_meta( $review_id, '_gym_review_reviewer_name', true );
            $verified     = get_post_meta( $review_id, '_gym_review_verified', true );
            $product_link = get_post_meta( $review_id, '_gym_review_product_link', true );
            $pros         = get_post_meta( $review_id, '_gym_review_pros', true );
            $cons         = get_post_meta( $review_id, '_gym_review_cons', true );
        ?>
            <article class="review-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <?php if ( $product ) : ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( $product ); ?></a></h3>
                    <?php else : ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php endif; ?>

                    <?php if ( $rating ) : ?>
                        <div class="card-meta">
                            <span class="stars">
                                <?php
                                $full = floor( $rating );
                                for ( $i = 0; $i < $full; $i++ ) {
                                    echo '<span class="star-full">&#9733;</span>';
                                }
                                for ( $i = $full; $i < 5; $i++ ) {
                                    echo '<span class="star-empty">&#9734;</span>';
                                }
                                ?>
                            </span>
                            <span><?php echo esc_html( $rating ); ?>/5</span>
                            <?php if ( $verified ) : ?>
                                <span class="badge badge-all-levels"><?php _e( 'Geverifieerd', 'gym-community' ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $reviewer ) : ?>
                        <p style="font-size: 0.85rem; color: var(--color-text-light); margin-bottom: 8px;">
                            <?php _e( 'Door', 'gym-community' ); ?> <strong><?php echo esc_html( $reviewer ); ?></strong>
                            &mdash; <?php echo get_the_date(); ?>
                        </p>
                    <?php endif; ?>

                    <?php the_excerpt(); ?>

                    <div class="card-actions">
                        <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Lees Review', 'gym-community' ); ?></a>
                        <?php if ( $product_link ) : ?>
                            <a href="<?php echo esc_url( $product_link ); ?>" target="_blank" rel="noopener" class="btn btn-small btn-outline"><?php _e( 'Bekijk Product', 'gym-community' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

    <?php gym_community_pagination(); ?>

<?php else : ?>

    <div class="content-area text-center">
        <h2><?php _e( 'Geen reviews gevonden', 'gym-community' ); ?></h2>
        <p><?php _e( 'Er zijn nog geen reviews geplaatst. Wees de eerste!', 'gym-community' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn"><?php _e( 'Terug naar Home', 'gym-community' ); ?></a>
    </div>

<?php endif; ?>

<?php
get_footer();
