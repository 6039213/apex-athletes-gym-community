<?php
/**
 * WordPress Production Configuration - SPL Sites Hosting
 * 
 * BELANGRIJK: Upload dit bestand als wp-config.php naar je server
 * 
 * @package WordPress
 */

// ** Database settings - SPL Sites Production ** //
define( 'DB_NAME', 'st1738846938' );
define( 'DB_USER', 'st1738846938' );
define( 'DB_PASSWORD', 'R5IFHm9dw7k6W6r' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts
 * BELANGRIJK: Gebruik andere keys voor productie!
 * Genereer nieuwe via: https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

/**
 * WordPress Database Table prefix
 */
$table_prefix = 'wp_';

/**
 * PRODUCTION MODE SETTINGS
 * Voor live server - debugging uitgeschakeld
 */

// Disable WP_DEBUG mode
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Disable file editing in admin (security best practice)
define( 'DISALLOW_FILE_EDIT', true );

// Set memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

// Enable auto-updates for core
define( 'WP_AUTO_UPDATE_CORE', true );

// Set WordPress URLs
define( 'WP_HOME', 'https://st1738846938.splsites.nl' );
define( 'WP_SITEURL', 'https://st1738846938.splsites.nl' );

// Force SSL
define( 'FORCE_SSL_ADMIN', true );

/**
 * Absolute path to WordPress directory
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
