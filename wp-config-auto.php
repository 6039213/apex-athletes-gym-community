<?php
/**
 * WordPress Configuration - Auto Environment Detection
 * 
 * Dit bestand detecteert automatisch of je in Docker (lokaal) of Productie (live) draait
 * en laadt de juiste configuratie.
 * 
 * INSTALLATIE:
 * 1. Hernoem dit bestand naar: wp-config.php
 * 2. Upload naar WordPress root (lokaal EN live server)
 * 3. Configureer wp-config-local.php en wp-config-production.php
 * 
 * @package WordPress
 */

/**
 * Detecteer omgeving
 * 
 * Methode 1: Check of we in Docker draaien (DB_HOST bevat 'db')
 * Methode 2: Check of specifieke environment variable bestaat
 * Methode 3: Check hostname
 */
function detect_environment() {
    // Check 1: Docker database host
    if ( getenv('WORDPRESS_DB_HOST') === 'db:3306' ) {
        return 'docker';
    }
    
    // Check 2: Hostname check
    $hostname = gethostname();
    if ( strpos($hostname, 'docker') !== false || strpos($hostname, 'container') !== false ) {
        return 'docker';
    }
    
    // Check 3: Check of we op localhost draaien (Docker poort 8080)
    if ( isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost:8080') !== false ) {
        return 'docker';
    }
    
    // Check 4: Check of specifiek bestand bestaat (alleen lokaal)
    if ( file_exists(__DIR__ . '/.docker-env') ) {
        return 'docker';
    }
    
    // Check 5: Check of we op live server draaien
    if ( isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'splsites.nl') !== false ) {
        return 'production';
    }
    
    // Default: production (veiligste optie)
    return 'production';
}

// Detecteer huidige omgeving
$environment = detect_environment();

// Laad juiste configuratie bestand
if ( $environment === 'docker' ) {
    // LOKAAL - Docker omgeving
    if ( file_exists(__DIR__ . '/wp-config-local.php') ) {
        require_once __DIR__ . '/wp-config-local.php';
    } else {
        die('ERROR: wp-config-local.php niet gevonden! Maak dit bestand aan voor Docker configuratie.');
    }
} else {
    // LIVE - Productie omgeving
    if ( file_exists(__DIR__ . '/wp-config-production.php') ) {
        require_once __DIR__ . '/wp-config-production.php';
    } else {
        die('ERROR: wp-config-production.php niet gevonden! Maak dit bestand aan voor productie configuratie.');
    }
}

// Debug info (alleen als WP_DEBUG enabled is)
if ( defined('WP_DEBUG') && WP_DEBUG ) {
    error_log('WordPress Environment: ' . $environment);
    error_log('Database Host: ' . DB_HOST);
    error_log('Database Name: ' . DB_NAME);
}
