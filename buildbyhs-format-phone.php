<?php
/**
 * File: buildbyhs-format-phone.php
 * Function: buildbyhs_format_phone_number
 * Description: Formats a raw phone number into a standardized, human-readable format
 *              with optional country‐code handling, using proper sanitization and escaping.
 *
 * Usage:
 * // International format with explicit country code
 * echo buildbyhs_format_phone_number( '+15551234567', '1' ); // +1 (555) 123-4567
 *
 * // US 10-digit format
 * echo buildbyhs_format_phone_number( '(555)1234567' );      // (555) 123-4567
 *
 * // Short or non-standard numbers will be sanitized but not reformatted
 * echo buildbyhs_format_phone_number( '12345' );             // 12345
 */

if ( ! function_exists( 'buildbyhs_format_phone_number' ) ) {
    function buildbyhs_format_phone_number( $number, $country_code = '' ) {
        // 1) Sanitize inputs
        $raw_input = sanitize_text_field( $number );
        $digits    = preg_replace( '/\D+/', '', $raw_input );

        // 2) Handle optional country code
        if ( $country_code ) {
            $cc     = preg_replace( '/\D+/', '', sanitize_text_field( $country_code ) );
            $digits = ltrim( $digits, '0' );
            $digits = $cc . $digits;
        }

        $len       = strlen( $digits );
        $formatted = '';

        // 3) Format numbers longer than 10 digits: +CC (AAA) BBB-CCCC
        if ( $len > 10 ) {
            $cc_part  = '+' . substr( $digits, 0, $len - 10 );
            $area     = substr( $digits, -10, 3 );
            $first    = substr( $digits, -7, 3 );
            $last     = substr( $digits, -4 );
            $formatted = sprintf(
                '%s (%s) %s-%s',
                esc_html( $cc_part ),
                esc_html( $area ),
                esc_html( $first ),
                esc_html( $last )
            );

        // 4) Format exactly 10 digits: (AAA) BBB-CCCC
        } elseif ( $len === 10 ) {
            $area     = substr( $digits, 0, 3 );
            $first    = substr( $digits, 3, 3 );
            $last     = substr( $digits, 6 );
            $formatted = sprintf(
                '(%s) %s-%s',
                esc_html( $area ),
                esc_html( $first ),
                esc_html( $last )
            );

        // 5) Fallback for shorter or unexpected lengths: raw digits
        } else {
            $formatted = esc_html( $digits );
        }

        return $formatted;
    }
}
