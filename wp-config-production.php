<?php
/**
 * WordPress Production Configuration Template
 * 
 * BELANGRIJK: 
 * 1. Kopieer dit bestand naar wp-config.php op de LIVE server
 * 2. Pas ALLE database credentials aan
 * 3. Genereer NIEUWE security keys via https://api.wordpress.org/secret-key/1.1/salt/
 * 4. Zet WP_DEBUG op false
 * 
 * @package WordPress
 */

// ** Database settings - PAS AAN VOOR JOUW SERVER ** //
define( 'DB_NAME', 'jouw_database_naam' );
define( 'DB_USER', 'jouw_database_user' );
define( 'DB_PASSWORD', 'jouw_database_wachtwoord' );
define( 'DB_HOST', 'localhost' ); // Of IP van database server
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts
 * BELANGRIJK: Genereer NIEUWE keys voor productie!
 * https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY',         'plaats hier nieuwe key' );
define( 'SECURE_AUTH_KEY',  'plaats hier nieuwe key' );
define( 'LOGGED_IN_KEY',    'plaats hier nieuwe key' );
define( 'NONCE_KEY',        'plaats hier nieuwe key' );
define( 'AUTH_SALT',        'plaats hier nieuwe key' );
define( 'SECURE_AUTH_SALT', 'plaats hier nieuwe key' );
define( 'LOGGED_IN_SALT',   'plaats hier nieuwe key' );
define( 'NONCE_SALT',       'plaats hier nieuwe key' );

/**
 * WordPress Database Table prefix
 */
$table_prefix = 'wp_';

/**
 * PRODUCTION MODE SETTINGS
 * Voor live server - debugging uitgeschakeld
 */

// Disable WP_DEBUG mode in production
define( 'WP_DEBUG', false );

// Log errors maar toon ze niet op scherm
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Disable file editing in admin (SECURITY!)
define( 'DISALLOW_FILE_EDIT', true );

// Set memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

// Force SSL for admin (als je SSL certificaat hebt)
// define( 'FORCE_SSL_ADMIN', true );

// Enable auto-updates for core (aanbevolen voor security)
define( 'WP_AUTO_UPDATE_CORE', true );

// Increase auto-save interval (optioneel, vermindert server load)
define( 'AUTOSAVE_INTERVAL', 300 ); // 5 minuten

// Limit post revisions (optioneel, bespaart database ruimte)
define( 'WP_POST_REVISIONS', 5 );

// Set cookie domain (pas aan naar jouw domein)
// define( 'COOKIE_DOMAIN', '.jouwdomein.nl' );

/**
 * Absolute path to WordPress directory
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
