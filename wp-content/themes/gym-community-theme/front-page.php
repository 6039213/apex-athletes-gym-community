<?php
/**
 * Front Page Template - Apex Athletes
 *
 * Homepage met hero section, community features, recente activiteiten,
 * reviews en call-to-action secties. Volledig gebaseerd op de Apex Athletes
 * huisstijl met CSS custom properties.
 *
 * @package Gym_Community_Theme
 * @since 2.0.0
 */

get_header();

$hero_subtitle = get_theme_mod( 'gym_community_hero_subtitle', 'Jouw ultieme fitness community voor trainingen, reviews en evenementen. Bereik je doelen met Apex Athletes.' );
?>

</div><!-- Close .container from header -->
</main><!-- Close main temporarily for full-width hero -->

<!-- ===== Hero Section ===== -->
<section class="hero-section">
    <div class="hero-content">
        <h1><?php _e( 'Welkom bij Apex Athletes', 'gym-community' ); ?></h1>
        <p class="hero-subtitle">
            <?php echo esc_html( $hero_subtitle ); ?>
        </p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn btn-primary btn-large">
                <?php _e( 'Bekijk Activiteiten', 'gym-community' ); ?>
            </a>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_review' ) ); ?>" class="btn btn-secondary btn-large">
                <?php _e( 'Lees Reviews', 'gym-community' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- ===== Community Features ===== -->
<section class="community-features">
    <div class="container">
        <div class="section-title">
            <h2><?php _e( 'Onze Community', 'gym-community' ); ?></h2>
            <p><?php _e( 'Ontdek alles wat Apex Athletes te bieden heeft voor jouw fitnessreis.', 'gym-community' ); ?></p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <span>&#9829;</span>
                </div>
                <h3><?php _e( 'Trainingen & Lessen', 'gym-community' ); ?></h3>
                <p><?php _e( 'Van yoga tot HIIT, ontdek onze professionele trainingen met gecertificeerde trainers.', 'gym-community' ); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <span>&#9733;</span>
                </div>
                <h3><?php _e( 'Product Reviews', 'gym-community' ); ?></h3>
                <p><?php _e( 'Eerlijke reviews van fitnessproducten, supplementen en sportkleding door onze community.', 'gym-community' ); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <span>&#9992;</span>
                </div>
                <h3><?php _e( 'Evenementen', 'gym-community' ); ?></h3>
                <p><?php _e( 'Doe mee aan community events, workshops en wedstrijden. Samen sterker worden.', 'gym-community' ); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <span>&#9998;</span>
                </div>
                <h3><?php _e( 'Inschrijfsysteem', 'gym-community' ); ?></h3>
                <p><?php _e( 'Meld je eenvoudig aan voor activiteiten met ons online registratiesysteem.', 'gym-community' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ===== Statistics Section ===== -->
<?php
$activities_count = wp_count_posts( 'gym_activity' );
$reviews_count    = wp_count_posts( 'gym_review' );
$activities_num   = isset( $activities_count->publish ) ? $activities_count->publish : 0;
$reviews_num      = isset( $reviews_count->publish ) ? $reviews_count->publish : 0;
?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?php echo esc_html( $activities_num ); ?>+</span>
                <span class="stat-label"><?php _e( 'Activiteiten', 'gym-community' ); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo esc_html( $reviews_num ); ?>+</span>
                <span class="stat-label"><?php _e( 'Reviews', 'gym-community' ); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo esc_html( wp_count_posts()->publish ); ?>+</span>
                <span class="stat-label"><?php _e( 'Nieuwsberichten', 'gym-community' ); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo esc_html( count_users()['total_users'] ); ?>+</span>
                <span class="stat-label"><?php _e( 'Leden', 'gym-community' ); ?></span>
            </div>
        </div>
    </div>
</section>

<main class="site-main">
<div class="container">

<!-- ===== Upcoming Activities ===== -->
<?php
$upcoming_activities = new WP_Query( array(
    'post_type'      => 'gym_activity',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'meta_key'       => '_gym_activity_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array(
        array(
            'key'     => '_gym_activity_date',
            'value'   => date( 'Y-m-d' ),
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    ),
) );

if ( $upcoming_activities->have_posts() ) :
?>
<section class="recent-activities">
    <div class="section-title">
        <h2><?php _e( 'Komende Activiteiten', 'gym-community' ); ?></h2>
        <p><?php _e( 'Meld je aan voor onze volgende trainingen en evenementen.', 'gym-community' ); ?></p>
    </div>

    <div class="posts-grid">
        <?php
        while ( $upcoming_activities->have_posts() ) :
            $upcoming_activities->the_post();
            $activity_id = get_the_ID();
            $date       = get_post_meta( $activity_id, '_gym_activity_date', true );
            $time       = get_post_meta( $activity_id, '_gym_activity_time', true );
            $trainer    = get_post_meta( $activity_id, '_gym_activity_trainer', true );
            $difficulty = get_post_meta( $activity_id, '_gym_activity_difficulty', true );
        ?>
            <article class="activity-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="card-meta">
                        <?php if ( $date ) : ?>
                            <span><?php echo esc_html( date_i18n( 'j F Y', strtotime( $date ) ) ); ?></span>
                        <?php endif; ?>
                        <?php if ( $time ) : ?>
                            <span><?php echo esc_html( $time ); ?></span>
                        <?php endif; ?>
                        <?php if ( $trainer ) : ?>
                            <span><?php echo esc_html( $trainer ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ( $difficulty ) : ?>
                        <span class="badge badge-<?php echo esc_attr( $difficulty ); ?>">
                            <?php echo esc_html( ucfirst( str_replace( '-', ' ', $difficulty ) ) ); ?>
                        </span>
                    <?php endif; ?>
                    <div class="card-actions">
                        <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Details', 'gym-community' ); ?></a>
                        <a href="<?php the_permalink(); ?>#registration" class="btn btn-small btn-outline"><?php _e( 'Inschrijven', 'gym-community' ); ?></a>
                    </div>
                </div>
            </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="text-center mt-3">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn btn-dark">
            <?php _e( 'Alle Activiteiten Bekijken', 'gym-community' ); ?>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- ===== Latest Reviews ===== -->
<?php
$latest_reviews = new WP_Query( array(
    'post_type'      => 'gym_review',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

if ( $latest_reviews->have_posts() ) :
?>
<section class="recent-reviews-section">
    <div class="section-title">
        <h2><?php _e( 'Laatste Reviews', 'gym-community' ); ?></h2>
        <p><?php _e( 'Ontdek wat onze community vindt van de nieuwste fitnessproducten.', 'gym-community' ); ?></p>
    </div>

    <div class="posts-grid">
        <?php
        while ( $latest_reviews->have_posts() ) :
            $latest_reviews->the_post();
            $review_id    = get_the_ID();
            $product      = get_post_meta( $review_id, '_gym_review_product', true );
            $rating       = get_post_meta( $review_id, '_gym_review_rating', true );
            $verified     = get_post_meta( $review_id, '_gym_review_verified', true );
            $product_link = get_post_meta( $review_id, '_gym_review_product_link', true );
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
                                for ( $i = 0; $i < $full; $i++ ) echo '<span class="star-full">&#9733;</span>';
                                for ( $i = $full; $i < 5; $i++ ) echo '<span class="star-empty">&#9734;</span>';
                                ?>
                            </span>
                            <span><?php echo esc_html( $rating ); ?>/5</span>
                            <?php if ( $verified ) : ?>
                                <span class="badge badge-all-levels"><?php _e( 'Geverifieerd', 'gym-community' ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php the_excerpt(); ?>

                    <div class="card-actions">
                        <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Lees Meer', 'gym-community' ); ?></a>
                        <?php if ( $product_link ) : ?>
                            <a href="<?php echo esc_url( $product_link ); ?>" target="_blank" rel="noopener" class="btn btn-small btn-outline"><?php _e( 'Bekijk Product', 'gym-community' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="text-center mt-3">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_review' ) ); ?>" class="btn btn-dark">
            <?php _e( 'Alle Reviews Bekijken', 'gym-community' ); ?>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- ===== Latest News ===== -->
<?php
$latest_news = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

if ( $latest_news->have_posts() ) :
?>
<section class="recent-posts">
    <div class="section-title">
        <h2><?php _e( 'Laatste Nieuws', 'gym-community' ); ?></h2>
        <p><?php _e( 'Blijf op de hoogte van het laatste nieuws uit de Apex Athletes community.', 'gym-community' ); ?></p>
    </div>

    <div class="posts-grid">
        <?php
        while ( $latest_news->have_posts() ) :
            $latest_news->the_post();
        ?>
            <article class="post-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'gym-community-featured' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="post-card-content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="entry-meta">
                        <?php echo get_the_date(); ?> | <?php the_author(); ?>
                    </div>
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-small"><?php _e( 'Lees Meer', 'gym-community' ); ?></a>
                </div>
            </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<!-- ===== CTA Section ===== -->
<section class="cta-section">
    <h2><?php _e( 'Klaar om te beginnen?', 'gym-community' ); ?></h2>
    <p><?php _e( 'Word lid van de Apex Athletes community en bereik je fitnessdoelen samen met gelijkgestemden.', 'gym-community' ); ?></p>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>" class="btn btn-large btn-secondary">
        <?php _e( 'Bekijk Onze Activiteiten', 'gym-community' ); ?>
    </a>
</section>

<!-- ===== Page Content (if any) ===== -->
<?php
while ( have_posts() ) :
    the_post();
    $content = get_the_content();
    if ( ! empty( $content ) ) :
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'content-area mt-4' ); ?>>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    </article>
<?php
    endif;
endwhile;

get_footer();
