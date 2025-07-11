<?php
/**
 * File: buildbyhs-obfuscate-email.php
 * Function: buildbyhs_obfuscate_email
 * Description: Converts an email address into a spam-resistant format using antispambot(),
 *              properly sanitizes and escapes all output.
 *
 * Usage:
 * // Somewhere in your theme or plugin:
 * echo buildbyhs_obfuscate_email( 'user@example.com' );
 */

if ( ! function_exists( 'buildbyhs_obfuscate_email' ) ) {
    function buildbyhs_obfuscate_email( $email ) {
        // 1) Sanitize input
        $email = sanitize_email( $email );
        if ( ! is_email( $email ) ) {
            return ''; // invalid email
        }

        // 2) Obfuscate for display
        $obfuscated = antispambot( $email ); 

        // 3) Build href (we keep the real email here, but it's escaped)
        $href = 'mailto:' . $email;

        // 4) Return safely—href is URL-escaped, text is HTML-escaped
        return '<a href="' . esc_url( $href ) . '">' . esc_html( $obfuscated ) . '</a>';
    }
}
