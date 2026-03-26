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
    <header class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php gym_community_site_branding(); ?>
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    ☰ Menu
                </button>
            </div>
            <?php gym_community_primary_navigation(); ?>
        </div>
    </header>

    <main id="main" class="site-main">
        <div class="container">
