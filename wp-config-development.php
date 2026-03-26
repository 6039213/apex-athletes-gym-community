<?php
/**
 * WordPress Development Configuration
 * 
 * BELANGRIJK: Kopieer dit bestand naar wp-config.php voor lokale development
 * wp-config.php staat in .gitignore en wordt NIET gecommit naar Git
 * 
 * @package WordPress
 */

// ** Database settings - Lokale development ** //
define( 'DB_NAME', 'Apex_Athletes' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts
 * BELANGRIJK: Gebruik andere keys voor productie!
 * Genereer nieuwe via: https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY',         'dDPgsn45^cnW?gpd^-Z8gZw/Kg>xRBOFPvGL;XmL:*Vlg23{QM48}XMrPZ3U9~|Q' );
define( 'SECURE_AUTH_KEY',  'F)Fs+Ij}omYwO^R2INmWt5SYtD#Nff^S&rK=(J82#)1GQ.()Q2)RpKZ-cYR!5>:l' );
define( 'LOGGED_IN_KEY',    '{J) Dj(,zt%0(Lc|#qJ$59qm%7MXCn_636IDc`O;#t?yiqgUt(V9y|<X&Uf/nm-!' );
define( 'NONCE_KEY',        'e@4E[R03@5:8CAX8a3G]&Ew|M&p&jug|&/9,sA~jn8[S*B:0vkHp6ls[z>w<!O:?' );
define( 'AUTH_SALT',        '[F;a^d%DM_H)-&$fvpktK ~/r`gp,Qs48Yokj!7=Ax3_e?xPh*xuFuZ1yLS^J+QO' );
define( 'SECURE_AUTH_SALT', ']/~|l@Ug6`Tugn^^6e{mjq 2G#>,Y%?`z+XQA|-)<&o=_7Jm<J17N<c 1V#e5nA!' );
define( 'LOGGED_IN_SALT',   '<JKHoGOw&ezF%c_k]MUhY7yC-~@[reBl,RCGyQn-[?756G_#GD+:Zn1LU4-A=KWR' );
define( 'NONCE_SALT',       'F:{1Ymq~:zR;Wnqr-C2,kkD{xsENxzV$~JqI0e@WlQCL38f1wzgy5A}K8%]?)z%<' );

/**
 * WordPress Database Table prefix
 */
$table_prefix = 'wp_';

/**
 * DEVELOPMENT MODE SETTINGS
 * Voor lokale development - debugging volledig ingeschakeld
 */

// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to wp-content/debug.log
define( 'WP_DEBUG_LOG', true );

// Display errors and warnings on screen
define( 'WP_DEBUG_DISPLAY', true );

// Use dev versions of core JS and CSS files (non-minified)
define( 'SCRIPT_DEBUG', true );

// Additional helpful constants for development
@ini_set( 'display_errors', 1 );

// Disable file editing in admin (security best practice)
define( 'DISALLOW_FILE_EDIT', true );

// Set memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

// Enable auto-updates for core (optional, kan uit voor development)
define( 'WP_AUTO_UPDATE_CORE', false );

/**
 * Absolute path to WordPress directory
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
