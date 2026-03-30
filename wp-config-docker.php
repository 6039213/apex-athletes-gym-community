<?php
/**
 * Docker configuration for the local WordPress environment.
 *
 * @package WordPress
 */

if ( ! function_exists( 'gym_community_env' ) ) {
	function gym_community_env( $key, $default = '' ) {
		$value = getenv( $key );

		return false !== $value && '' !== $value ? $value : $default;
	}

	function gym_community_env_bool( $key, $default = false ) {
		$value = getenv( $key );

		if ( false === $value || '' === $value ) {
			return $default;
		}

		return in_array( strtolower( $value ), array( '1', 'true', 'yes', 'on' ), true );
	}
}

define( 'DB_NAME', gym_community_env( 'WORDPRESS_DB_NAME', 'Apex_Athletes' ) );
define( 'DB_USER', gym_community_env( 'WORDPRESS_DB_USER', 'wordpress' ) );
define( 'DB_PASSWORD', gym_community_env( 'WORDPRESS_DB_PASSWORD', 'wordpress' ) );
define( 'DB_HOST', gym_community_env( 'WORDPRESS_DB_HOST', 'db:3306' ) );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',         'dDPgsn45^cnW?gpd^-Z8gZw/Kg>xRBOFPvGL;XmL:*Vlg23{QM48}XMrPZ3U9~|Q' );
define( 'SECURE_AUTH_KEY',  'F)Fs+Ij}omYwO^R2INmWt5SYtD#Nff^S&rK=(J82#)1GQ.()Q2)RpKZ-cYR!5>:l' );
define( 'LOGGED_IN_KEY',    '{J) Dj(,zt%0(Lc|#qJ$59qm%7MXCn_636IDc`O;#t?yiqgUt(V9y|<X&Uf/nm-!' );
define( 'NONCE_KEY',        'e@4E[R03@5:8CAX8a3G]&Ew|M&p&jug|&/9,sA~jn8[S*B:0vkHp6ls[z>w<!O:?' );
define( 'AUTH_SALT',        '[F;a^d%DM_H)-&$fvpktK ~/r`gp,Qs48Yokj!7=Ax3_e?xPh*xuFuZ1yLS^J+QO' );
define( 'SECURE_AUTH_SALT', ']/~|l@Ug6`Tugn^^6e{mjq 2G#>,Y%?`z+XQA|-)<&o=_7Jm<J17N<c 1V#e5nA!' );
define( 'LOGGED_IN_SALT',   '<JKHoGOw&ezF%c_k]MUhY7yC-~@[reBl,RCGyQn-[?756G_#GD+:Zn1LU4-A=KWR' );
define( 'NONCE_SALT',       'F:{1Ymq~:zR;Wnqr-C2,kkD{xsENxzV$~JqI0e@WlQCL38f1wzgy5A}K8%]?)z%<' );

$table_prefix = gym_community_env( 'WORDPRESS_TABLE_PREFIX', 'wp_' );

define( 'WP_HOME', gym_community_env( 'WP_HOME', 'http://localhost:8080' ) );
define( 'WP_SITEURL', gym_community_env( 'WP_SITEURL', gym_community_env( 'WP_HOME', 'http://localhost:8080' ) ) );

define( 'WP_DEBUG', gym_community_env_bool( 'WP_DEBUG', true ) );
define( 'WP_DEBUG_LOG', gym_community_env_bool( 'WP_DEBUG_LOG', true ) );
define( 'WP_DEBUG_DISPLAY', gym_community_env_bool( 'WP_DEBUG_DISPLAY', true ) );
define( 'SCRIPT_DEBUG', gym_community_env_bool( 'SCRIPT_DEBUG', true ) );
define( 'DISALLOW_FILE_EDIT', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'WP_MEMORY_LIMIT', gym_community_env( 'WP_MEMORY_LIMIT', '256M' ) );
define( 'WP_ENVIRONMENT_TYPE', gym_community_env( 'WP_ENVIRONMENT_TYPE', 'local' ) );
define( 'FS_METHOD', 'direct' );

@ini_set( 'display_errors', WP_DEBUG_DISPLAY ? '1' : '0' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
