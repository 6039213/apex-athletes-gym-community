<?php
/**
 * Gym Admin Settings - Apex Athletes
 *
 * Beheert de admin instellingen, documentatie en statistieken
 * van de Gym Community plugin. Bevat hooks voor thema-integratie,
 * export functionaliteit en uitgebreide configuratie-opties.
 *
 * @package Gym_Community_Plugin
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gym_Admin {

    /**
     * Constructor: registreer admin hooks
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_notices', array( $this, 'theme_compatibility_notice' ) );
        add_action( 'admin_init', array( $this, 'handle_export' ) );

        /**
         * Hook: gym_community_admin_loaded
         * Vuurt af wanneer de admin class volledig geladen is.
         * Gebruik dit om extra admin functionaliteit toe te voegen.
         */
        do_action( 'gym_community_admin_loaded', $this );
    }

    /**
     * Registreer admin menu pagina's
     */
    public function add_settings_page() {
        add_menu_page(
            __( 'Apex Athletes - Gym Community', 'gym-community-plugin' ),
            __( 'Gym Community', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-heart',
            30
        );

        add_submenu_page(
            'gym-community-settings',
            __( 'Instellingen', 'gym-community-plugin' ),
            __( 'Instellingen', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-settings',
            array( $this, 'render_settings_page' )
        );

        add_submenu_page(
            'gym-community-settings',
            __( 'Documentatie', 'gym-community-plugin' ),
            __( 'Documentatie', 'gym-community-plugin' ),
            'manage_options',
            'gym-community-docs',
            array( $this, 'render_docs_page' )
        );

        /**
         * Hook: gym_community_admin_menu
         * Voeg extra submenu pagina's toe aan het Gym Community menu.
         *
         * @param string $parent_slug De slug van het parent menu.
         */
        do_action( 'gym_community_admin_menu', 'gym-community-settings' );
    }

    /**
     * Registreer alle plugin instellingen met de Settings API
     */
    public function register_settings() {
        // Registreer individuele opties met sanitize callbacks
        register_setting( 'gym_community_settings', 'gym_community_email_notifications', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1',
        ) );
        register_setting( 'gym_community_settings', 'gym_community_admin_email', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default'           => get_option( 'admin_email' ),
        ) );
        register_setting( 'gym_community_settings', 'gym_community_auto_approve_reviews', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '0',
        ) );
        register_setting( 'gym_community_settings', 'gym_community_registration_limit', array(
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 5,
        ) );
        register_setting( 'gym_community_settings', 'gym_community_confirmation_email_subject', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => __( 'Bevestiging inschrijving - Apex Athletes', 'gym-community-plugin' ),
        ) );
        register_setting( 'gym_community_settings', 'gym_community_show_past_activities', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '0',
        ) );

        // Algemene Instellingen sectie
        add_settings_section(
            'gym_community_general',
            __( 'Algemene Instellingen', 'gym-community-plugin' ),
            array( $this, 'general_section_callback' ),
            'gym-community-settings'
        );

        // E-mail Instellingen sectie
        add_settings_section(
            'gym_community_email_section',
            __( 'E-mail Instellingen', 'gym-community-plugin' ),
            array( $this, 'email_section_callback' ),
            'gym-community-settings'
        );

        // Velden: Algemeen
        add_settings_field(
            'gym_community_auto_approve_reviews',
            __( 'Reviews automatisch goedkeuren', 'gym-community-plugin' ),
            array( $this, 'auto_approve_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        add_settings_field(
            'gym_community_registration_limit',
            __( 'Inschrijflimiet per gebruiker', 'gym-community-plugin' ),
            array( $this, 'registration_limit_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        add_settings_field(
            'gym_community_show_past_activities',
            __( 'Verlopen activiteiten tonen', 'gym-community-plugin' ),
            array( $this, 'show_past_activities_callback' ),
            'gym-community-settings',
            'gym_community_general'
        );

        // Velden: E-mail
        add_settings_field(
            'gym_community_email_notifications',
            __( 'E-mailnotificaties', 'gym-community-plugin' ),
            array( $this, 'email_notifications_callback' ),
            'gym-community-settings',
            'gym_community_email_section'
        );

        add_settings_field(
            'gym_community_admin_email',
            __( 'Admin e-mailadres', 'gym-community-plugin' ),
            array( $this, 'admin_email_callback' ),
            'gym-community-settings',
            'gym_community_email_section'
        );

        add_settings_field(
            'gym_community_confirmation_email_subject',
            __( 'Bevestigingsmail onderwerp', 'gym-community-plugin' ),
            array( $this, 'confirmation_subject_callback' ),
            'gym-community-settings',
            'gym_community_email_section'
        );

        /**
         * Hook: gym_community_register_settings
         * Registreer extra instellingen voor de plugin.
         */
        do_action( 'gym_community_register_settings' );
    }

    /**
     * Toon melding als het Apex Athletes thema niet actief is
     */
    public function theme_compatibility_notice() {
        $current_theme = wp_get_theme();
        $theme_slug    = $current_theme->get_template();

        if ( $theme_slug !== 'gym-community-theme' ) {
            $screen = get_current_screen();
            if ( $screen && strpos( $screen->id, 'gym-community' ) !== false ) {
                ?>
                <div class="notice notice-warning gym-admin-notice">
                    <p>
                        <strong><?php _e( 'Gym Community Plugin:', 'gym-community-plugin' ); ?></strong>
                        <?php printf(
                            __( 'Voor de beste ervaring gebruik je het %sApex Athletes - Gym Community Theme%s. Het huidige thema (%s) wordt ondersteund maar mist mogelijk bepaalde integratiefuncties.', 'gym-community-plugin' ),
                            '<strong>',
                            '</strong>',
                            esc_html( $current_theme->get( 'Name' ) )
                        ); ?>
                    </p>
                </div>
                <?php
            }
        }
    }

    /**
     * Verwerk CSV export van registraties
     */
    public function handle_export() {
        if ( ! isset( $_GET['gym_export'] ) || $_GET['gym_export'] !== 'registrations' ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'gym_export_registrations' ) ) {
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'gym_registrations';
        $rows  = $wpdb->get_results( "SELECT * FROM $table ORDER BY created_at DESC", ARRAY_A );

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=registraties-' . date( 'Y-m-d' ) . '.csv' );

        $output = fopen( 'php://output', 'w' );
        if ( ! empty( $rows ) ) {
            fputcsv( $output, array_keys( $rows[0] ) );
            foreach ( $rows as $row ) {
                fputcsv( $output, $row );
            }
        }
        fclose( $output );
        exit;
    }

    // =========================================================================
    // Settings Callbacks
    // =========================================================================

    public function general_section_callback() {
        echo '<p>' . __( 'Configureer de algemene instellingen voor de Gym Community plugin.', 'gym-community-plugin' ) . '</p>';
    }

    public function email_section_callback() {
        echo '<p>' . __( 'Beheer e-mailnotificaties en bevestigingsberichten.', 'gym-community-plugin' ) . '</p>';
    }

    public function email_notifications_callback() {
        $value = get_option( 'gym_community_email_notifications', '1' );
        ?>
        <label>
            <input type="checkbox" name="gym_community_email_notifications" value="1" <?php checked( $value, '1' ); ?>>
            <?php _e( 'Stuur e-mailnotificaties bij nieuwe inschrijvingen', 'gym-community-plugin' ); ?>
        </label>
        <?php
    }

    public function admin_email_callback() {
        $value = get_option( 'gym_community_admin_email', get_option( 'admin_email' ) );
        ?>
        <input type="email" name="gym_community_admin_email" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php _e( 'E-mailadres voor admin-notificaties bij inschrijvingen.', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function auto_approve_callback() {
        $value = get_option( 'gym_community_auto_approve_reviews', '0' );
        ?>
        <label>
            <input type="checkbox" name="gym_community_auto_approve_reviews" value="1" <?php checked( $value, '1' ); ?>>
            <?php _e( 'Reviews automatisch publiceren zonder handmatige goedkeuring', 'gym-community-plugin' ); ?>
        </label>
        <p class="description"><?php _e( 'Indien uitgeschakeld moeten reviews handmatig goedgekeurd worden.', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function registration_limit_callback() {
        $value = get_option( 'gym_community_registration_limit', '5' );
        ?>
        <input type="number" name="gym_community_registration_limit" value="<?php echo esc_attr( $value ); ?>" min="1" max="50" class="small-text">
        <p class="description"><?php _e( 'Maximum aantal activiteiten waarvoor een gebruiker zich kan inschrijven.', 'gym-community-plugin' ); ?></p>
        <?php
    }

    public function show_past_activities_callback() {
        $value = get_option( 'gym_community_show_past_activities', '0' );
        ?>
        <label>
            <input type="checkbox" name="gym_community_show_past_activities" value="1" <?php checked( $value, '1' ); ?>>
            <?php _e( 'Toon ook verlopen activiteiten in het archief', 'gym-community-plugin' ); ?>
        </label>
        <?php
    }

    public function confirmation_subject_callback() {
        $value = get_option( 'gym_community_confirmation_email_subject', __( 'Bevestiging inschrijving - Apex Athletes', 'gym-community-plugin' ) );
        ?>
        <input type="text" name="gym_community_confirmation_email_subject" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php _e( 'Onderwerpregel van de bevestigingsmail naar deelnemers.', 'gym-community-plugin' ); ?></p>
        <?php
    }

    // =========================================================================
    // Render Settings Page
    // =========================================================================

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'gym_community_messages', 'gym_community_message', __( 'Instellingen opgeslagen.', 'gym-community-plugin' ), 'updated' );
        }

        settings_errors( 'gym_community_messages' );
        ?>
        <div class="wrap gym-admin-wrap">
            <h1><?php _e( 'Apex Athletes - Gym Community Instellingen', 'gym-community-plugin' ); ?></h1>

            <?php $this->render_theme_status(); ?>

            <form action="options.php" method="post" class="gym-settings-form">
                <?php
                settings_fields( 'gym_community_settings' );
                do_settings_sections( 'gym-community-settings' );
                submit_button( __( 'Instellingen Opslaan', 'gym-community-plugin' ) );
                ?>
            </form>

            <hr>

            <h2><?php _e( 'Plugin Statistieken', 'gym-community-plugin' ); ?></h2>
            <?php $this->render_statistics(); ?>

            <hr>

            <h2><?php _e( 'Data Export', 'gym-community-plugin' ); ?></h2>
            <p><?php _e( 'Exporteer registratiegegevens als CSV-bestand.', 'gym-community-plugin' ); ?></p>
            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=gym-community-settings&gym_export=registrations' ), 'gym_export_registrations' ) ); ?>" class="button button-secondary">
                <?php _e( 'Registraties Exporteren (CSV)', 'gym-community-plugin' ); ?>
            </a>

            <?php
            /**
             * Hook: gym_community_settings_after
             * Voeg extra content toe onder de instellingenpagina.
             */
            do_action( 'gym_community_settings_after' );
            ?>
        </div>
        <?php
    }

    /**
     * Toon thema compatibiliteit status
     */
    private function render_theme_status() {
        $current_theme = wp_get_theme();
        $is_compatible = ( $current_theme->get_template() === 'gym-community-theme' );
        $status_class  = $is_compatible ? 'notice-success' : 'notice-warning';
        $status_text   = $is_compatible
            ? __( 'Apex Athletes thema actief - Volledige integratie beschikbaar.', 'gym-community-plugin' )
            : sprintf( __( 'Huidig thema: %s. Voor volledige integratie activeer het Apex Athletes thema.', 'gym-community-plugin' ), $current_theme->get( 'Name' ) );
        ?>
        <div class="notice <?php echo esc_attr( $status_class ); ?> gym-admin-notice" style="margin: 15px 0;">
            <p><strong><?php _e( 'Thema Status:', 'gym-community-plugin' ); ?></strong> <?php echo esc_html( $status_text ); ?></p>
        </div>
        <?php
    }

    /**
     * Toon plugin statistieken met counters
     */
    private function render_statistics() {
        $activities_count = wp_count_posts( 'gym_activity' );
        $reviews_count    = wp_count_posts( 'gym_review' );
        $users_count      = count_users();

        global $wpdb;
        $table = $wpdb->prefix . 'gym_registrations';
        $table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) === $table;

        $registrations_count = 0;
        $pending_count       = 0;
        if ( $table_exists ) {
            $registrations_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'confirmed'" );
            $pending_count       = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'pending'" );
        }

        $activities_num = isset( $activities_count->publish ) ? $activities_count->publish : 0;
        $reviews_num    = isset( $reviews_count->publish ) ? $reviews_count->publish : 0;
        ?>
        <div class="gym-stats">
            <div class="stat-box">
                <h3><?php echo esc_html( $activities_num ); ?></h3>
                <p><?php _e( 'Actieve Activiteiten', 'gym-community-plugin' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo esc_html( $reviews_num ); ?></h3>
                <p><?php _e( 'Gepubliceerde Reviews', 'gym-community-plugin' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo esc_html( $registrations_count ); ?></h3>
                <p><?php _e( 'Bevestigde Inschrijvingen', 'gym-community-plugin' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo esc_html( $pending_count ); ?></h3>
                <p><?php _e( 'Wachtende Inschrijvingen', 'gym-community-plugin' ); ?></p>
            </div>
            <div class="stat-box">
                <h3><?php echo esc_html( $users_count['total_users'] ); ?></h3>
                <p><?php _e( 'Geregistreerde Leden', 'gym-community-plugin' ); ?></p>
            </div>
        </div>
        <?php
    }

    // =========================================================================
    // Render Documentation Page
    // =========================================================================

    public function render_docs_page() {
        ?>
        <div class="wrap gym-admin-wrap">
            <h1><?php _e( 'Apex Athletes - Plugin Documentatie', 'gym-community-plugin' ); ?></h1>

            <div class="gym-docs">
                <h2><?php _e( 'Beschikbare Shortcodes', 'gym-community-plugin' ); ?></h2>

                <div class="shortcode-doc">
                    <h3><code>[gym_activities]</code></h3>
                    <p><?php _e( 'Toon een lijst van gym activiteiten met details, afbeeldingen en inschrijfknoppen.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>limit</code> - <?php _e( 'Aantal activiteiten (standaard: 10)', 'gym-community-plugin' ); ?></li>
                        <li><code>type</code> - <?php _e( 'Filter op activiteitstype slug', 'gym-community-plugin' ); ?></li>
                        <li><code>upcoming</code> - <?php _e( 'Alleen toekomstige activiteiten tonen (yes/no, standaard: yes)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Voorbeeld:', 'gym-community-plugin' ); ?></strong> <code>[gym_activities limit="5" type="cardio"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[gym_schedule]</code></h3>
                    <p><?php _e( 'Toon een weekrooster van alle geplande activiteiten.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>days</code> - <?php _e( 'Aantal dagen om te tonen (standaard: 7)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Voorbeeld:', 'gym-community-plugin' ); ?></strong> <code>[gym_schedule days="14"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[recent_reviews]</code></h3>
                    <p><?php _e( 'Toon recente product- en dienstreviews met sterrenratings.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>limit</code> - <?php _e( 'Aantal reviews (standaard: 5)', 'gym-community-plugin' ); ?></li>
                        <li><code>category</code> - <?php _e( 'Filter op reviewcategorie slug', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Voorbeeld:', 'gym-community-plugin' ); ?></strong> <code>[recent_reviews limit="3" category="supplementen"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[product_reviews]</code></h3>
                    <p><?php _e( 'Toon reviews voor een specifiek product.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>product</code> - <?php _e( 'Productnaam om op te filteren', 'gym-community-plugin' ); ?></li>
                        <li><code>limit</code> - <?php _e( 'Aantal reviews (standaard: 10)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Voorbeeld:', 'gym-community-plugin' ); ?></strong> <code>[product_reviews product="Whey Protein" limit="5"]</code></p>
                </div>

                <div class="shortcode-doc">
                    <h3><code>[gym_registration_form]</code></h3>
                    <p><?php _e( 'Toon een AJAX inschrijfformulier voor een activiteit met capaciteitscontrole.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Parameters:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>activity_id</code> - <?php _e( 'Activiteit ID (standaard: huidig post ID)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <p><strong><?php _e( 'Voorbeeld:', 'gym-community-plugin' ); ?></strong> <code>[gym_registration_form activity_id="123"]</code></p>
                </div>

                <hr>

                <h2><?php _e( 'Custom Post Types', 'gym-community-plugin' ); ?></h2>

                <div class="shortcode-doc">
                    <h3><?php _e( 'Gym Activiteiten', 'gym-community-plugin' ); ?> (<code>gym_activity</code>)</h3>
                    <p><?php _e( 'Beheer activiteiten en lessen met details zoals datum, tijd, trainer, capaciteit, locatie en moeilijkheidsgraad.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Meta Velden:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>_gym_activity_date</code> - <?php _e( 'Datum van de activiteit', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_time</code> - <?php _e( 'Starttijd', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_trainer</code> - <?php _e( 'Naam van de trainer', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_capacity</code> - <?php _e( 'Maximum aantal deelnemers', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_duration</code> - <?php _e( 'Duur in minuten', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_location</code> - <?php _e( 'Locatie', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_activity_difficulty</code> - <?php _e( 'Niveau (beginner, intermediate, advanced, all-levels)', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <h4><?php _e( 'Taxonomie:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>activity_type</code> - <?php _e( 'Type activiteit (bijv. Cardio, Yoga, HIIT)', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>

                <div class="shortcode-doc">
                    <h3><?php _e( 'Reviews', 'gym-community-plugin' ); ?> (<code>gym_review</code>)</h3>
                    <p><?php _e( 'Beheer product- en dienstreviews met ratings, voor- en nadelen, en externe links.', 'gym-community-plugin' ); ?></p>
                    <h4><?php _e( 'Meta Velden:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>_gym_review_product</code> - <?php _e( 'Productnaam', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_rating</code> - <?php _e( 'Beoordeling (1-5)', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_reviewer_name</code> - <?php _e( 'Naam van de reviewer', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_product_link</code> - <?php _e( 'Externe productlink', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_verified</code> - <?php _e( 'Geverifieerde aankoop (checkbox)', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_pros</code> - <?php _e( 'Pluspunten', 'gym-community-plugin' ); ?></li>
                        <li><code>_gym_review_cons</code> - <?php _e( 'Minpunten', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <h4><?php _e( 'Taxonomie:', 'gym-community-plugin' ); ?></h4>
                    <ul>
                        <li><code>review_category</code> - <?php _e( 'Reviewcategorie (bijv. Supplementen, Kleding, Equipment)', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>

                <hr>

                <h2><?php _e( 'Beschikbare Hooks', 'gym-community-plugin' ); ?></h2>

                <div class="shortcode-doc">
                    <h3><?php _e( 'Action Hooks', 'gym-community-plugin' ); ?></h3>
                    <ul>
                        <li><code>gym_community_admin_loaded</code> - <?php _e( 'Vuurt af wanneer de admin class geladen is.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_admin_menu</code> - <?php _e( 'Voeg extra submenu items toe.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_register_settings</code> - <?php _e( 'Registreer extra plugin instellingen.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_settings_after</code> - <?php _e( 'Voeg content toe onder de instellingenpagina.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_after_registration</code> - <?php _e( 'Vuurt af na een succesvolle inschrijving.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_before_registration</code> - <?php _e( 'Vuurt af voor het verwerken van een inschrijving.', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>

                <div class="shortcode-doc">
                    <h3><?php _e( 'Filter Hooks', 'gym-community-plugin' ); ?></h3>
                    <ul>
                        <li><code>gym_community_activity_card_html</code> - <?php _e( 'Filter de HTML output van een activiteitenkaart.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_review_card_html</code> - <?php _e( 'Filter de HTML output van een reviewkaart.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_registration_fields</code> - <?php _e( 'Voeg extra velden toe aan het inschrijfformulier.', 'gym-community-plugin' ); ?></li>
                        <li><code>gym_community_confirmation_email</code> - <?php _e( 'Filter het bevestigingsmail bericht.', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>

                <hr>

                <h2><?php _e( 'Thema Integratie', 'gym-community-plugin' ); ?></h2>
                <div class="shortcode-doc">
                    <p><?php _e( 'De plugin is ontworpen om naadloos samen te werken met het Apex Athletes thema. Het thema biedt:', 'gym-community-plugin' ); ?></p>
                    <ul>
                        <li><?php _e( 'Archief templates voor activiteiten en reviews', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Single templates met volledige metadata weergave', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Homepage integratie met recente activiteiten en reviews secties', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Consistente CSS custom properties voor kleuren en fonts', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Customizer opties voor branding aanpassingen', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>

                <hr>

                <h2><?php _e( 'Versiegeschiedenis', 'gym-community-plugin' ); ?></h2>
                <div class="shortcode-doc">
                    <h4>v2.0.0</h4>
                    <ul>
                        <li><?php _e( 'Volledige Apex Athletes branding integratie', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'CSS custom properties voor thema-consistentie', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Uitgebreide admin instellingen met e-mail configuratie', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'CSV export van registraties', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Thema compatibiliteitscontrole', 'gym-community-plugin' ); ?></li>
                        <li><?php _e( 'Hooks documentatie voor extensibiliteit', 'gym-community-plugin' ); ?></li>
                    </ul>
                    <h4>v1.0.0</h4>
                    <ul>
                        <li><?php _e( 'Eerste release met activiteiten, reviews, registraties en shortcodes.', 'gym-community-plugin' ); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}

new Gym_Admin();
