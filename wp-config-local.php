<?php
/**
 * WordPress Local Configuration - Docker Environment
 * 
 * Dit bestand wordt ALLEEN gebruikt in je lokale Docker omgeving
 * Upload dit bestand NIET naar de live server!
 * 
 * @package WordPress
 */

// ** Database settings - Docker ** //
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'Apex_Athletes' );
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: 'wordpress' );
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: 'wordpress' );
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'db:3306' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts - DEVELOPMENT
 */
define( 'AUTH_KEY',         'local-dev-auth-key-change-in-production' );
define( 'SECURE_AUTH_KEY',  'local-dev-secure-auth-key-change-in-production' );
define( 'LOGGED_IN_KEY',    'local-dev-logged-in-key-change-in-production' );
define( 'NONCE_KEY',        'local-dev-nonce-key-change-in-production' );
define( 'AUTH_SALT',        'local-dev-auth-salt-change-in-production' );
define( 'SECURE_AUTH_SALT', 'local-dev-secure-auth-salt-change-in-production' );
define( 'LOGGED_IN_SALT',   'local-dev-logged-in-salt-change-in-production' );
define( 'NONCE_SALT',       'local-dev-nonce-salt-change-in-production' );

/**
 * WordPress Database Table prefix
 */
$table_prefix = 'wp_';

/**
 * DEVELOPMENT MODE SETTINGS
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
@ini_set( 'display_errors', 1 );

// Allow file editing in development
define( 'DISALLOW_FILE_EDIT', false );

// Memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

// Disable auto-updates in development
define( 'WP_AUTO_UPDATE_CORE', false );

// WordPress URLs for Docker
define( 'WP_HOME', 'http://localhost:8080' );
define( 'WP_SITEURL', 'http://localhost:8080' );

// Disable SSL in development
define( 'FORCE_SSL_ADMIN', false );

/**
 * Absolute path to WordPress directory
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
