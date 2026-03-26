<?php
/**
 * The template for displaying single reviews
 *
 * @package Gym_Community_Theme
 */

get_header();
?>

<div class="content-area">
    <?php
    while ( have_posts() ) :
        the_post();
        $review_id = get_the_ID();
        $product = get_post_meta( $review_id, '_gym_review_product', true );
        $rating = get_post_meta( $review_id, '_gym_review_rating', true );
        $reviewer_name = get_post_meta( $review_id, '_gym_review_reviewer_name', true );
        $product_link = get_post_meta( $review_id, '_gym_review_product_link', true );
        $verified = get_post_meta( $review_id, '_gym_review_verified', true );
        $pros = get_post_meta( $review_id, '_gym_review_pros', true );
        $cons = get_post_meta( $review_id, '_gym_review_cons', true );
        
        function display_review_stars( $rating ) {
            $output = '';
            $full_stars = floor( $rating );
            $half_star = ( $rating - $full_stars ) >= 0.5;
            
            for ( $i = 0; $i < $full_stars; $i++ ) {
                $output .= '<span class="star star-full">★</span>';
            }
            
            if ( $half_star ) {
                $output .= '<span class="star star-half">★</span>';
            }
            
            $empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );
            for ( $i = 0; $i < $empty_stars; $i++ ) {
                $output .= '<span class="star star-empty">☆</span>';
            }
            
            return '<span class="stars">' . $output . '</span>';
        }
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header review-header">
                <?php if ( $product ) : ?>
                    <div class="review-product-name">
                        <h2><?php echo esc_html( $product ); ?></h2>
                        <?php if ( $verified ) : ?>
                            <span class="verified-badge">✓ Verified Purchase</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $rating ) : ?>
                    <div class="review-rating-display">
                        <?php echo display_review_stars( $rating ); ?>
                        <span class="rating-number"><?php echo esc_html( $rating ); ?>/5</span>
                    </div>
                <?php endif; ?>
                
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                
                <div class="review-meta-info">
                    <?php if ( $reviewer_name ) : ?>
                        <span class="reviewer-name">By <?php echo esc_html( $reviewer_name ); ?></span>
                    <?php else : ?>
                        <span class="reviewer-name">By <?php the_author(); ?></span>
                    <?php endif; ?>
                    <span class="review-date"><?php echo get_the_date(); ?></span>
                    <?php
                    $terms = get_the_terms( $review_id, 'review_category' );
                    if ( $terms && ! is_wp_error( $terms ) ) :
                        ?>
                        <span class="review-category">
                            <?php
                            foreach ( $terms as $term ) {
                                echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a> ';
                            }
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="review-product-image">
                    <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
                
                <?php if ( $pros || $cons ) : ?>
                    <div class="pros-cons-section">
                        <?php if ( $pros ) : ?>
                            <div class="pros-box">
                                <h3>✓ Pros</h3>
                                <div class="pros-content">
                                    <?php echo wpautop( esc_html( $pros ) ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $cons ) : ?>
                            <div class="cons-box">
                                <h3>✗ Cons</h3>
                                <div class="cons-content">
                                    <?php echo wpautop( esc_html( $cons ) ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $product_link ) : ?>
                    <div class="product-link-section">
                        <a href="<?php echo esc_url( $product_link ); ?>" target="_blank" rel="noopener" class="btn btn-primary">
                            Buy <?php echo esc_html( $product ); ?> Now →
                        </a>
                        <p class="disclaimer">This is an external link. We may earn a commission from purchases.</p>
                    </div>
                <?php endif; ?>
            </div>

            <footer class="entry-footer">
                <?php
                if ( comments_open() || get_comments_number() ) :
                    ?>
                    <div class="review-comments-section">
                        <h3>Questions & Answers</h3>
                        <?php comments_template(); ?>
                    </div>
                    <?php
                endif;
                ?>
            </footer>
        </article>

        <div class="related-reviews">
            <h3>More Reviews in This Category</h3>
            <?php
            $terms = get_the_terms( $review_id, 'review_category' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_ids = wp_list_pluck( $terms, 'term_id' );
                
                $related_args = array(
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
                );
                
                $related_reviews = new WP_Query( $related_args );
                
                if ( $related_reviews->have_posts() ) :
                    echo '<div class="related-reviews-grid">';
                    while ( $related_reviews->have_posts() ) :
                        $related_reviews->the_post();
                        $rel_rating = get_post_meta( get_the_ID(), '_gym_review_rating', true );
                        $rel_product = get_post_meta( get_the_ID(), '_gym_review_product', true );
                        ?>
                        <div class="related-review-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="related-review-thumb">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'gym-community-thumbnail' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <h4><a href="<?php the_permalink(); ?>"><?php echo esc_html( $rel_product ); ?></a></h4>
                            <?php if ( $rel_rating ) : ?>
                                <div class="related-rating">
                                    <?php echo display_review_stars( $rel_rating ); ?>
                                </div>
                            <?php endif; ?>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-small">Read Review</a>
                        </div>
                        <?php
                    endwhile;
                    echo '</div>';
                    wp_reset_postdata();
                else :
                    echo '<p>No related reviews found.</p>';
                endif;
            }
            ?>
        </div>

        <?php
    endwhile;
    ?>
</div>

<style>
.review-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.review-product-name {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.review-product-name h2 {
    margin: 0;
    color: #2c3e50;
}

.verified-badge {
    background: #27ae60;
    color: #fff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.review-rating-display {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.review-rating-display .stars {
    font-size: 24px;
    color: #f39c12;
}

.review-rating-display .rating-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
}

.review-meta-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    font-size: 0.9rem;
    color: #7f8c8d;
}

.review-product-image {
    margin-bottom: 30px;
    text-align: center;
}

.review-product-image img {
    max-width: 600px;
    border-radius: 8px;
}

.pros-cons-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.pros-box,
.cons-box {
    padding: 20px;
    border-radius: 8px;
}

.pros-box {
    background: #d4edda;
    border-left: 4px solid #27ae60;
}

.cons-box {
    background: #f8d7da;
    border-left: 4px solid #e74c3c;
}

.pros-box h3 {
    color: #27ae60;
    margin-top: 0;
}

.cons-box h3 {
    color: #e74c3c;
    margin-top: 0;
}

.product-link-section {
    background: #fff3cd;
    border-left: 4px solid #f39c12;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
    text-align: center;
}

.product-link-section .btn {
    margin-bottom: 10px;
}

.product-link-section .disclaimer {
    font-size: 0.85rem;
    color: #856404;
    margin: 0;
}

.related-reviews {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 2px solid #ecf0f1;
}

.related-reviews h3 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.related-reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.related-review-card {
    background: #fff;
    border: 1px solid #ecf0f1;
    border-radius: 8px;
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.related-review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.related-review-thumb {
    margin-bottom: 10px;
}

.related-review-thumb img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
}

.related-review-card h4 {
    margin: 10px 0;
    font-size: 1.1rem;
}

.related-review-card h4 a {
    color: #2c3e50;
    text-decoration: none;
}

.related-review-card h4 a:hover {
    color: #e74c3c;
}

.related-rating {
    margin: 10px 0;
}

.related-rating .stars {
    color: #f39c12;
    font-size: 16px;
}

@media (max-width: 768px) {
    .review-header {
        padding: 20px;
    }
    
    .review-product-name {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .pros-cons-section {
        grid-template-columns: 1fr;
    }
    
    .related-reviews-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
get_footer();
