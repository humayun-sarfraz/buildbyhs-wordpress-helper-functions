<?php
/**
 * File: buildbyhs-cache-control.php
 * Function: buildbyhs_send_cache_control_headers
 * Description: Sends cache-control and expires headers for static assets or pages,
 *              allowing filterable customization of directives.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-cache-control.php';
 * // Hook on init or appropriate action:
 * add_action( 'send_headers', 'buildbyhs_send_cache_control_headers' );
 */

if ( ! function_exists( 'buildbyhs_send_cache_control_headers' ) ) {
    /**
     * Sends HTTP headers to control caching on the client side.
     *
     * @param WP $wp WordPress environment instance (unused).
     */
    function buildbyhs_send_cache_control_headers( $wp ) {
        // Default directives
        $directives = array(
            'public',
            'max-age=3600', // seconds
            'must-revalidate',
        );

        /**
         * Filter the cache control directives.
         *
         * @param array $directives Array of directive strings.
         */
        $directives = apply_filters( 'buildbyhs_cache_control_directives', $directives );

        // Sanitize and build header value
        $safe = array();
        foreach ( $directives as $dir ) {
            $safe[] = sanitize_text_field( $dir );
        }
        $header = implode( ', ', $safe );

        // Send headers
        header( 'Cache-Control: ' . $header );
        // Optionally set Expires timestamp
        $expires = apply_filters( 'buildbyhs_cache_control_expires', time() + 3600 );
        if ( $expires && is_numeric( $expires ) ) {
            header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', intval( $expires ) ) . ' GMT' );
        }
    }
}
