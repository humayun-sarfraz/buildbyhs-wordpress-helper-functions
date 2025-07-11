<?php
/**
 * File: buildbyhs-google-analytics.php
 * Functions: buildbyhs_add_google_analytics, buildbyhs_enqueue_ga_script
 * Description: Adds Google Analytics tracking code to the site head or footer,
 *              with configurable Tracking ID via constant or filter and proper escaping.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-google-analytics.php';
 * add_action( 'wp_head', 'buildbyhs_add_google_analytics' );
 */

if ( ! function_exists( 'buildbyhs_add_google_analytics' ) ) {
    /**
     * Echoes Google Analytics <script> snippet with configured Tracking ID.
     */
    function buildbyhs_add_google_analytics() {
        // Allow constant or filter for GA ID
        $tracking_id = defined( 'BUILDBYHS_GA_ID' ) ? BUILDBYHS_GA_ID : '';
        $tracking_id = apply_filters( 'buildbyhs_google_analytics_id', $tracking_id );
        $tracking_id = sanitize_text_field( $tracking_id );

        if ( empty( $tracking_id ) ) {
            return;
        }
        ?>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $tracking_id ); ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);} gtag('js', new Date());
          gtag('config', '<?php echo esc_js( $tracking_id ); ?>');
        </script>
        <!-- End Google Analytics -->
        <?php
    }
}
