<?php
/**
 * File: buildbyhs-security-headers.php
 * Function: buildbyhs_send_security_headers
 * Description: Sends common HTTP security headers to harden the site,
 *              with filterable directives and proper sanitization.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-security-headers.php';
 * add_action( 'send_headers', 'buildbyhs_send_security_headers' );
 */

if ( ! function_exists( 'buildbyhs_send_security_headers' ) ) {
    /**
     * Send HTTP headers for security hardening.
     *
     * @param WP $wp WordPress environment instance (unused).
     */
    function buildbyhs_send_security_headers( $wp ) {
        $headers = array(
            // Prevent clickjacking
            'X-Frame-Options'           => 'SAMEORIGIN',
            // Prevent MIME-type sniffing
            'X-Content-Type-Options'    => 'nosniff',
            // Enable XSS filtering
            'X-XSS-Protection'          => '1; mode=block',
            // Referrer policy
            'Referrer-Policy'           => 'strict-origin-when-cross-origin',
            // Content Security Policy (basic)
            'Content-Security-Policy'   => "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';"
        );

        /**
         * Filter the security headers array.
         *
         * @param array $headers Key-value pairs of header => value.
         */
        $headers = apply_filters( 'buildbyhs_security_headers', $headers );

        foreach ( $headers as $key => $value ) {
            $h = sanitize_text_field( $value );
            header( esc_html( $key ) . ': ' . $h );
        }
    }
}
