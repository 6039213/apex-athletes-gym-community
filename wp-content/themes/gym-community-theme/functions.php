<?php
/**
 * Gym Community Theme Functions
 *
 * @package Gym_Community_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function gym_community_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 630, true );
    
    // Add custom image sizes
    add_image_size( 'gym-community-featured', 800, 400, true );
    add_image_size( 'gym-community-thumbnail', 300, 200, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'gym-community' ),
        'footer'  => __( 'Footer Menu', 'gym-community' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'f4f4f4',
    ) );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for wide alignment
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'gym_community_setup' );

/**
 * Set the content width in pixels
 */
function gym_community_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'gym_community_content_width', 1200 );
}
add_action( 'after_setup_theme', 'gym_community_content_width', 0 );

/**
 * Register widget areas
 */
function gym_community_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'gym-community' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'gym-community' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 1', 'gym-community' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add widgets here to appear in footer column 1.', 'gym-community' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 2', 'gym-community' ),
        'id'            => 'footer-2',
        'description'   => __( 'Add widgets here to appear in footer column 2.', 'gym-community' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 3', 'gym-community' ),
        'id'            => 'footer-3',
        'description'   => __( 'Add widgets here to appear in footer column 3.', 'gym-community' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'gym_community_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function gym_community_scripts() {
    // Enqueue Google Fonts (Montserrat + Oswald - Apex Athletes Stijlgids)
    wp_enqueue_style( 'gym-community-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap', array(), null );

    // Enqueue main stylesheet
    wp_enqueue_style( 'gym-community-style', get_stylesheet_uri(), array( 'gym-community-google-fonts' ), '2.0.0' );

    // Enqueue custom JavaScript
    wp_enqueue_script( 'gym-community-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '2.0.0', true );

    // Enqueue comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'gym_community_scripts' );

/**
 * Custom excerpt length
 */
function gym_community_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'gym_community_excerpt_length', 999 );

/**
 * Custom excerpt more
 */
function gym_community_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'gym_community_excerpt_more' );

/**
 * Add custom classes to body
 */
function gym_community_body_classes( $classes ) {
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    if ( is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'gym_community_body_classes' );

/**
 * Display custom logo or site title
 */
function gym_community_site_branding() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        ?>
        <h1 class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <?php bloginfo( 'name' ); ?>
            </a>
        </h1>
        <?php
        $description = get_bloginfo( 'description', 'display' );
        if ( $description || is_customize_preview() ) {
            ?>
            <p class="site-description"><?php echo esc_html( $description ); ?></p>
            <?php
        }
    }
}

/**
 * Display navigation menu
 */
function gym_community_primary_navigation() {
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'container'      => 'nav',
        'container_class'=> 'main-navigation',
        'fallback_cb'    => 'gym_community_fallback_menu',
    ) );
}

/**
 * Fallback menu if no menu is set
 */
function gym_community_fallback_menu() {
    ?>
    <nav class="main-navigation">
        <ul id="primary-menu">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
            <?php wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) ); ?>
        </ul>
    </nav>
    <?php
}

/**
 * Display footer navigation menu
 */
function gym_community_footer_navigation() {
    if ( has_nav_menu( 'footer' ) ) {
        wp_nav_menu( array(
            'theme_location' => 'footer',
            'menu_id'        => 'footer-menu',
            'container'      => 'nav',
            'container_class'=> 'footer-navigation',
            'depth'          => 1,
        ) );
    }
}

/**
 * Display post meta information
 */
function gym_community_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );

    $posted_on = sprintf(
        '<span class="posted-on">Posted on %s</span>',
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    $byline = sprintf(
        '<span class="byline"> by %s</span>',
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo $posted_on . $byline;
}

/**
 * Display post footer meta
 */
function gym_community_entry_footer() {
    if ( 'post' === get_post_type() ) {
        $categories_list = get_the_category_list( ', ' );
        if ( $categories_list ) {
            printf( '<span class="cat-links">Categories: %s</span>', $categories_list );
        }

        $tags_list = get_the_tag_list( '', ', ' );
        if ( $tags_list ) {
            printf( ' <span class="tags-links">Tags: %s</span>', $tags_list );
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo ' <span class="comments-link">';
        comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' );
        echo '</span>';
    }
}

/**
 * Pagination for archive pages
 */
function gym_community_pagination() {
    the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => __( '&laquo; Previous', 'gym-community' ),
        'next_text' => __( 'Next &raquo;', 'gym-community' ),
        'class'     => 'pagination',
    ) );
}

/**
 * Add custom fields support notice
 */
function gym_community_acf_notice() {
    if ( ! class_exists( 'ACF' ) && current_user_can( 'install_plugins' ) ) {
        ?>
        <div class="notice notice-warning">
            <p><?php _e( 'Gym Community Theme: Advanced Custom Fields plugin is recommended for full functionality.', 'gym-community' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'gym_community_acf_notice' );

/**
 * Customizer additions
 */
function gym_community_customize_register( $wp_customize ) {
    // Apex Athletes Section
    $wp_customize->add_section( 'apex_athletes_settings', array(
        'title'    => __( 'Apex Athletes Instellingen', 'gym-community' ),
        'priority' => 30,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'gym_community_primary_color', array(
        'default'           => '#2C3E50',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gym_community_primary_color', array(
        'label'    => __( 'Primaire Kleur', 'gym-community' ),
        'section'  => 'apex_athletes_settings',
    ) ) );

    // Accent Color
    $wp_customize->add_setting( 'gym_community_accent_color', array(
        'default'           => '#4ECDC4',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gym_community_accent_color', array(
        'label'    => __( 'Accent Kleur', 'gym-community' ),
        'section'  => 'apex_athletes_settings',
    ) ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'gym_community_hero_subtitle', array(
        'default'           => 'Jouw ultieme fitness community voor trainingen, reviews en evenementen.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gym_community_hero_subtitle', array(
        'label'   => __( 'Hero Subtitel', 'gym-community' ),
        'section' => 'apex_athletes_settings',
        'type'    => 'textarea',
    ) );

    // Footer Text
    $wp_customize->add_setting( 'gym_community_footer_text', array(
        'default'           => 'Apex Athletes is jouw community voor fitness, gezondheid en welzijn.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gym_community_footer_text', array(
        'label'   => __( 'Footer Tekst', 'gym-community' ),
        'section' => 'apex_athletes_settings',
        'type'    => 'textarea',
    ) );
}
add_action( 'customize_register', 'gym_community_customize_register' );

/**
 * Output custom CSS for primary color
 */
function gym_community_custom_css() {
    $primary_color = get_theme_mod( 'gym_community_primary_color', '#2C3E50' );
    $accent_color = get_theme_mod( 'gym_community_accent_color', '#4ECDC4' );
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr( $primary_color ); ?>;
            --color-accent: <?php echo esc_attr( $accent_color ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'gym_community_custom_css' );
