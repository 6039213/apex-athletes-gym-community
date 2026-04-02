<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="wrapper">
    <header class="site-header" role="banner">
        <div class="container">
            <div class="header-inner">
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php endif; ?>
                    <div class="site-branding-text">
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                        <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( $description || is_customize_preview() ) :
                        ?>
                            <p class="site-description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    ☰ Menu
                </button>

                <?php gym_community_primary_navigation(); ?>
            </div>
        </div>
    </header>

    <main id="main" class="site-main">
        <div class="container">
