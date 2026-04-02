<?php
/**
 * Single Template: Gym Review
 *
 * Toont een enkele review met product details, rating, pros/cons,
 * externe productlink en gerelateerde reviews.
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();

/**
 * Helper: toon sterren-rating als HTML
 */
if ( ! function_exists( 'gym_display_review_stars' ) ) {
    function gym_display_review_stars( $rating ) {
        $output     = '';
        $full_stars = floor( $rating );
        $half_star  = ( $rating - $full_stars ) >= 0.5;

        for ( $i = 0; $i < $full_stars; $i++ ) {
            $output .= '<span class="star-full">&#9733;</span>';
        }
        if ( $half_star ) {
            $output .= '<span class="star-half">&#9733;</span>';
        }
        $empty = 5 - $full_stars - ( $half_star ? 1 : 0 );
        for ( $i = 0; $i < $empty; $i++ ) {
            $output .= '<span class="star-empty">&#9734;</span>';
        }

        return '<span class="stars">' . $output . '</span>';
    }
}
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        $review_id     = get_the_ID();
        $product       = get_post_meta( $review_id, '_gym_review_product', true );
        $rating        = get_post_meta( $review_id, '_gym_review_rating', true );
        $reviewer_name = get_post_meta( $review_id, '_gym_review_reviewer_name', true );
        $product_link  = get_post_meta( $review_id, '_gym_review_product_link', true );
        $verified      = get_post_meta( $review_id, '_gym_review_verified', true );
        $pros          = get_post_meta( $review_id, '_gym_review_pros', true );
        $cons          = get_post_meta( $review_id, '_gym_review_cons', true );
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php if ( $product ) : ?>
                    <div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap; margin-bottom:15px;">
                        <h2 style="margin:0;"><?php echo esc_html( $product ); ?></h2>
                        <?php if ( $verified ) : ?>
                            <span class="badge badge-all-levels"><?php _e( 'Geverifieerde Aankoop', 'gym-community' ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $rating ) : ?>
                    <div class="card-meta" style="margin-bottom:15px;">
                        <?php echo gym_display_review_stars( $rating ); ?>
                        <span style="font-weight:700; font-size:1.1rem;"><?php echo esc_html( $rating ); ?>/5</span>
                    </div>
                <?php endif; ?>

                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                <div class="card-meta">
                    <?php if ( $reviewer_name ) : ?>
                        <span><?php _e( 'Door', 'gym-community' ); ?> <?php echo esc_html( $reviewer_name ); ?></span>
                    <?php else : ?>
                        <span><?php _e( 'Door', 'gym-community' ); ?> <?php the_author(); ?></span>
                    <?php endif; ?>
                    <span><?php echo get_the_date(); ?></span>
                    <?php
                    $terms = get_the_terms( $review_id, 'review_category' );
                    if ( $terms && ! is_wp_error( $terms ) ) :
                        foreach ( $terms as $term ) :
                    ?>
                        <span><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></span>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail" style="text-align:center; margin-bottom:30px;">
                    <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>

                <?php if ( $pros || $cons ) : ?>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px; margin:30px 0;">
                        <?php if ( $pros ) : ?>
                            <div style="padding:25px; border-radius:var(--radius-md); background:#D5F5E3; border-left:4px solid var(--color-success);">
                                <h4 style="color:var(--color-success); margin-top:0;"><?php _e( 'Pluspunten', 'gym-community' ); ?></h4>
                                <?php echo wpautop( esc_html( $pros ) ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( $cons ) : ?>
                            <div style="padding:25px; border-radius:var(--radius-md); background:#FADBD8; border-left:4px solid var(--color-danger);">
                                <h4 style="color:var(--color-danger); margin-top:0;"><?php _e( 'Minpunten', 'gym-community' ); ?></h4>
                                <?php echo wpautop( esc_html( $cons ) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $product_link ) : ?>
                    <div class="cta-section" style="margin:30px 0; border-radius:var(--radius-lg);">
                        <h3 style="color:var(--color-white);">
                            <?php printf( __( 'Bekijk %s', 'gym-community' ), esc_html( $product ) ); ?>
                        </h3>
                        <a href="<?php echo esc_url( $product_link ); ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-large">
                            <?php _e( 'Bekijk Product', 'gym-community' ); ?> &rarr;
                        </a>
                        <p style="font-size:0.8rem; margin-top:15px; opacity:0.8;">
                            <?php _e( 'Externe link. Mogelijk ontvangen wij een commissie bij aankopen.', 'gym-community' ); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <footer class="entry-footer">
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
            </footer>
        </article>

        <?php
        // Gerelateerde reviews
        $terms = get_the_terms( $review_id, 'review_category' );
        if ( $terms && ! is_wp_error( $terms ) ) :
            $term_ids = wp_list_pluck( $terms, 'term_id' );
            $related  = new WP_Query( array(
                'post_type'      => 'gym_review',
                'posts_per_page' => 3,
                'post__not_in'   => array( $review_id ),
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'review_category',
                        'field'    => 'term_id',
                        'terms'    => $term_ids,
                    ),
                ),
            ) );

            if ( $related->have_posts() ) :
        ?>
            <section class="recent-reviews-section mt-4">
                <div class="section-title">
                    <h2><?php _e( 'Gerelateerde Reviews', 'gym-community' ); ?></h2>
                </div>
                <div class="posts-grid">
                    <?php
                    while ( $related->have_posts() ) :
                        $related->the_post();
                        $rel_rating  = get_post_meta( get_the_ID(), '_gym_review_rating', true );
                        $rel_product = get_post_meta( get_the_ID(), '_gym_review_product', true );
                    ?>
                        <article class="review-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="card-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'gym-community-thumbnail' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( $rel_product ?: get_the_title() ); ?></a></h3>
                                <?php if ( $rel_rating ) : ?>
                                    <div class="card-meta">
                                        <?php echo gym_display_review_stars( $rel_rating ); ?>
                                        <span><?php echo esc_html( $rel_rating ); ?>/5</span>
                                    </div>
                                <?php endif; ?>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                <div class="card-actions">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Lees Review', 'gym-community' ); ?></a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
        <?php
            endif;
        endif;
        ?>

    <?php endwhile; ?>
</div>

<?php
get_footer();
