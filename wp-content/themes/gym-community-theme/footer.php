        </div><!-- .container -->
    </main><!-- #main -->

    <footer class="site-footer" role="contentinfo">
        <div class="container">
            <div class="footer-widgets">
                <div class="footer-widget footer-brand">
                    <h3><?php bloginfo( 'name' ); ?></h3>
                    <p><?php echo esc_html( get_theme_mod( 'gym_community_footer_text', 'Apex Athletes is jouw community voor fitness, gezondheid en welzijn. Sluit je aan bij onze community en bereik je fitnessdoelen.' ) ); ?></p>
                </div>

                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3><?php _e( 'Snelle Links', 'gym-community' ); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'gym-community' ); ?></a></li>
                            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'gym_activity' ) ); ?>"><?php _e( 'Activiteiten', 'gym-community' ); ?></a></li>
                            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'gym_review' ) ); ?>"><?php _e( 'Reviews', 'gym-community' ); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3><?php _e( 'Community', 'gym-community' ); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/nieuws/' ) ); ?>"><?php _e( 'Nieuws', 'gym-community' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php _e( 'Contact', 'gym-community' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/over-ons/' ) ); ?>"><?php _e( 'Over Ons', 'gym-community' ); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3><?php _e( 'Contact', 'gym-community' ); ?></h3>
                        <ul>
                            <li><?php _e( 'info@apexathletes.nl', 'gym-community' ); ?></li>
                            <li><?php _e( 'Amsterdam, Nederland', 'gym-community' ); ?></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <?php gym_community_footer_navigation(); ?>

            <div class="site-info">
                <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'Alle rechten voorbehouden.', 'gym-community' ); ?></p>
                <p><?php _e( 'Powered by', 'gym-community' ); ?> <a href="https://wordpress.org/">WordPress</a> | Apex Athletes Theme v2.0</p>
            </div>
        </div>
    </footer>
</div><!-- .wrapper -->

<?php wp_footer(); ?>
</body>
</html>
