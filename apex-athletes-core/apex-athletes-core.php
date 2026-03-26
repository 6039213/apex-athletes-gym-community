<?php
/**
 * Plugin Name: Apex Athletes Core
 * Description: Adds Apex Athletes styling, Google Fonts, and a Calorie & Macro Calculator via the [apex_calculator] shortcode.
 * Version: 1.0.0
 * Author: Apex Athletes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue frontend styles and scripts.
 */
function apex_athletes_core_enqueue_assets() {
    // Google Fonts: Montserrat + Open Sans
    wp_enqueue_style(
        'apex-athletes-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;500;600&display=swap',
        array(),
        null
    );

    // Main CSS
    wp_enqueue_style(
        'apex-athletes-core-css',
        plugin_dir_url( __FILE__ ) . 'assets/css/apex-athletes-core.css',
        array(),
        '1.0.0'
    );

    // Main JS
    wp_enqueue_script(
        'apex-athletes-core-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/apex-athletes-core.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'apex_athletes_core_enqueue_assets' );

/**
 * Shortcode callback for [apex_calculator]
 */
function apex_athletes_core_calculator_shortcode() {
    ob_start();
    ?>
    <div class="apex-calculator">
        <div class="apex-calculator__card">
            <h2 class="apex-calculator__title">Apex Calorie &amp; Macro Calculator</h2>
            <p class="apex-calculator__subtitle">
                Based on the Mifflin-St Jeor equation. All values use metric units.
            </p>

            <form class="apex-calculator__form" id="apex-calculator-form">
                <div class="apex-calculator__grid">
                    <div class="apex-calculator__field">
                        <label for="apex-age">Age</label>
                        <input type="number" id="apex-age" min="10" max="100" required placeholder="Years" />
                    </div>

                    <div class="apex-calculator__field">
                        <label for="apex-gender">Gender</label>
                        <select id="apex-gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="apex-calculator__field">
                        <label for="apex-weight">Weight (kg)</label>
                        <input type="number" id="apex-weight" min="30" max="250" step="0.1" required placeholder="kg" />
                    </div>

                    <div class="apex-calculator__field">
                        <label for="apex-height">Height (cm)</label>
                        <input type="number" id="apex-height" min="120" max="230" step="0.5" required placeholder="cm" />
                    </div>

                    <div class="apex-calculator__field">
                        <label for="apex-activity">Activity Level</label>
                        <select id="apex-activity" required>
                            <option value="1.2">Sedentary (little or no exercise)</option>
                            <option value="1.375">Light (1–3 days/week)</option>
                            <option value="1.55">Moderate (3–5 days/week)</option>
                            <option value="1.725">Heavy (6–7 days/week)</option>
                            <option value="1.9">Athlete (2x per day)</option>
                        </select>
                    </div>

                    <div class="apex-calculator__field">
                        <label for="apex-goal">Goal</label>
                        <select id="apex-goal" required>
                            <option value="maintenance">Maintenance</option>
                            <option value="cut">Cutting (fat loss)</option>
                            <option value="bulk">Bulking (muscle gain)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="apex-calculator__button">
                    Calculate
                </button>
            </form>

            <div class="apex-calculator__results" id="apex-results" aria-live="polite">
                <div class="apex-calculator__results-row">
                    <div class="apex-calculator__metric">
                        <span class="apex-calculator__metric-label">Maintenance Calories</span>
                        <span class="apex-calculator__metric-value" id="apex-maintenance">–</span>
                    </div>
                    <div class="apex-calculator__metric">
                        <span class="apex-calculator__metric-label">Goal Calories</span>
                        <span class="apex-calculator__metric-value" id="apex-goal-calories">–</span>
                    </div>
                </div>

                <div class="apex-calculator__macro-grid">
                    <div class="apex-calculator__macro-card">
                        <span class="apex-calculator__macro-label">Protein</span>
                        <span class="apex-calculator__macro-value" id="apex-protein">– g</span>
                    </div>
                    <div class="apex-calculator__macro-card">
                        <span class="apex-calculator__macro-label">Fats</span>
                        <span class="apex-calculator__macro-value" id="apex-fats">– g</span>
                    </div>
                    <div class="apex-calculator__macro-card">
                        <span class="apex-calculator__macro-label">Carbs</span>
                        <span class="apex-calculator__macro-value" id="apex-carbs">– g</span>
                    </div>
                </div>

                <p class="apex-calculator__note">
                    Macros are estimated using high-performance athlete defaults:
                    higher protein, controlled fats, remaining calories into carbs.
                </p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'apex_calculator', 'apex_athletes_core_calculator_shortcode' );

/**
 * Main app layout shortcode [apex_app]
 * Renders landing, feed, reels, forum and tools sections inside one experience.
 */
function apex_athletes_core_app_shortcode() {
    ob_start();
    ?>
    <div class="apex-app">
        <div class="apex-app__shell">
            <header class="apex-app__header">
                <div class="apex-app__brand">
                    <div class="apex-app__logo-circle">
                        <span class="apex-app__logo-mark">A</span>
                    </div>
                    <div class="apex-app__brand-text">
                        <span class="apex-app__brand-name">Apex Athletes</span>
                        <span class="apex-app__brand-tagline">Serious training. Clean community.</span>
                    </div>
                </div>
                <nav class="apex-app__nav">
                    <button class="apex-app__nav-btn" data-apex-section="home">Home</button>
                    <button class="apex-app__nav-btn" data-apex-section="feed">Hub</button>
                    <button class="apex-app__nav-btn" data-apex-section="reels">Reels</button>
                    <button class="apex-app__nav-btn" data-apex-section="forum">Forum</button>
                    <button class="apex-app__nav-btn" data-apex-section="tools">Tools</button>
                </nav>
            </header>

            <main class="apex-app__main">
                <section class="apex-app__section" data-apex-view="home">
                    <div class="apex-app__hero">
                        <div class="apex-app__hero-copy">
                            <p class="apex-app__eyebrow">High‑performance gym community</p>
                            <h1 class="apex-app__hero-title">Train above the noise.</h1>
                            <p class="apex-app__hero-body">
                                Apex Athletes is a focused space for lifters, competitors and coaches.
                                No fluff. Just training logs, evidence‑based discussions and tools that
                                keep you accountable.
                            </p>
                            <div class="apex-app__hero-cta-row">
                                <a href="#apex-tools" class="apex-app__primary-cta">Join the community</a>
                                <button class="apex-app__secondary-cta" data-apex-section="reels">
                                    Preview Apex Reels
                                </button>
                            </div>
                            <div class="apex-app__hero-pills">
                                <span class="apex-app__pill">Structured feed</span>
                                <span class="apex-app__pill">Forum boards</span>
                                <span class="apex-app__pill">Calorie &amp; macro tools</span>
                            </div>
                        </div>
                        <div class="apex-app__hero-panel">
                            <div class="apex-app__hero-card">
                                <p class="apex-app__hero-card-label">Today’s Check‑in Snapshot</p>
                                <p class="apex-app__hero-card-main">Lower body strength · RPE 8</p>
                                <p class="apex-app__hero-card-sub">
                                    Squat triples · RDL volume · walk + mobility.
                                </p>
                            </div>
                            <div class="apex-app__hero-stats">
                                <div class="apex-app__stat">
                                    <span class="apex-app__stat-label">Weekly sessions</span>
                                    <span class="apex-app__stat-value">5</span>
                                </div>
                                <div class="apex-app__stat">
                                    <span class="apex-app__stat-label">Avg sleep</span>
                                    <span class="apex-app__stat-value">7.8h</span>
                                </div>
                                <div class="apex-app__stat">
                                    <span class="apex-app__stat-label">Consistency</span>
                                    <span class="apex-app__stat-value">92%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="apex-app__section" data-apex-view="feed">
                    <div class="apex-app__section-header">
                        <h2>Community Hub</h2>
                        <p>Structured posts for training logs, insights and accountability.</p>
                    </div>
                    <div class="apex-app__feed">
                        <article class="apex-post">
                            <div class="apex-post__meta">
                                <span class="apex-post__tag apex-post__tag--training">Training Log</span>
                                <span class="apex-post__time">Today · 06:14</span>
                            </div>
                            <h3 class="apex-post__title">Heavy pull day · 3x3 deadlift @ 210kg</h3>
                            <p class="apex-post__body">
                                Focus on bar speed and clean lockouts. Back‑off volume kept submax to
                                leave room for the rest of the microcycle.
                            </p>
                        </article>

                        <article class="apex-post">
                            <div class="apex-post__meta">
                                <span class="apex-post__tag apex-post__tag--nutrition">Nutrition</span>
                                <span class="apex-post__time">Yesterday · 20:41</span>
                            </div>
                            <h3 class="apex-post__title">Travel day macro strategy</h3>
                            <p class="apex-post__body">
                                High‑protein breakfast, pack 2 shakes and 1 balanced meal. Keep fats moderate,
                                avoid random snacks and anchor your intake with pre‑logged options.
                            </p>
                        </article>

                        <article class="apex-post apex-post--media">
                            <div class="apex-post__meta">
                                <span class="apex-post__tag apex-post__tag--video">Video</span>
                                <span class="apex-post__time">This week</span>
                            </div>
                            <h3 class="apex-post__title">Front squat technique review</h3>
                            <div class="apex-post__video-shell">
                                <span class="apex-post__video-label">Reel preview</span>
                                <span class="apex-post__video-play">▶</span>
                            </div>
                            <p class="apex-post__body">
                                From rounded upper‑back to stacked posture in 3 cues: stance, bracing,
                                and bar path.
                            </p>
                        </article>
                    </div>
                </section>

                <section class="apex-app__section" data-apex-view="reels">
                    <div class="apex-app__section-header">
                        <h2>Apex Reels</h2>
                        <p>Vertical, focused clips you can skim between sets.</p>
                    </div>
                    <div class="apex-reels">
                        <div class="apex-reel">
                            <div class="apex-reel__badge">60s breakdown</div>
                            <h3 class="apex-reel__title">RPE for strength athletes</h3>
                            <p class="apex-reel__subtitle">
                                Quick visual guide to choosing the right load for today’s session.
                            </p>
                        </div>
                        <div class="apex-reel">
                            <div class="apex-reel__badge">Coach cue</div>
                            <h3 class="apex-reel__title">Bracing that actually holds</h3>
                            <p class="apex-reel__subtitle">
                                One breathing pattern to keep your torso locked on heavy squats.
                            </p>
                        </div>
                        <div class="apex-reel">
                            <div class="apex-reel__badge">Nutrition</div>
                            <h3 class="apex-reel__title">Pre‑lift carb timing</h3>
                            <p class="apex-reel__subtitle">
                                How to line up carbs for strength or hypertrophy sessions.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="apex-app__section" data-apex-view="forum">
                    <div class="apex-app__section-header">
                        <h2>Community Forum</h2>
                        <p>Clean boards for targeted, high‑signal discussion.</p>
                    </div>
                    <div class="apex-forum">
                        <article class="apex-forum__board">
                            <h3>Training</h3>
                            <p>Programming, technique, progression models, competition peaking.</p>
                            <ul>
                                <li>Block periodisation vs DUP for intermediates</li>
                                <li>Video form checks · lower/upper splits</li>
                            </ul>
                        </article>
                        <article class="apex-forum__board">
                            <h3>Nutrition</h3>
                            <p>Cutting, bulking, refeeds, tracking systems.</p>
                            <ul>
                                <li>When to transition from cut to maintenance</li>
                                <li>Macro tracking vs meal templates</li>
                            </ul>
                        </article>
                        <article class="apex-forum__board">
                            <h3>Gear</h3>
                            <p>Belts, sleeves, shoes, straps, tech and wearables.</p>
                            <ul>
                                <li>Best shoes for narrow‑stance squats</li>
                                <li>Minimalist gym bag setups</li>
                            </ul>
                        </article>
                        <article class="apex-forum__board">
                            <h3>Off‑topic</h3>
                            <p>Life, work, and everything that still impacts performance.</p>
                            <ul>
                                <li>Managing training with shift work</li>
                                <li>Sleep strategies for parents</li>
                            </ul>
                        </article>
                    </div>
                </section>

                <section class="apex-app__section" data-apex-view="tools" id="apex-tools">
                    <div class="apex-app__section-header">
                        <h2>Athlete Tools</h2>
                        <p>Calorie &amp; macro tools that match the rest of the Apex experience.</p>
                    </div>
                    <div class="apex-tools">
                        <div class="apex-tools__primary">
                            <?php echo do_shortcode( '[apex_calculator]' ); ?>
                        </div>
                        <div class="apex-tools__side">
                            <div class="apex-tools__summary">
                                <p class="apex-tools__summary-title">Macro balance snapshot</p>
                                <p class="apex-tools__summary-text">
                                    After calculating, your macro targets will appear as grams. 
                                    Use the bar below as a quick visual: protein leads, fats are controlled, 
                                    and remaining calories go to carbs.
                                </p>
                                <div class="apex-tools__macro-bar">
                                    <div class="apex-tools__macro-bar-segment apex-tools__macro-bar-segment--protein"></div>
                                    <div class="apex-tools__macro-bar-segment apex-tools__macro-bar-segment--fats"></div>
                                    <div class="apex-tools__macro-bar-segment apex-tools__macro-bar-segment--carbs"></div>
                                </div>
                                <div class="apex-tools__macro-legend">
                                    <span>Protein</span>
                                    <span>Fats</span>
                                    <span>Carbs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'apex_app', 'apex_athletes_core_app_shortcode' );

