<?php
/**
 * File: buildbyhs-format-price.php
 * Location: wp-content/themes/your-theme/inc/ or wp-content/plugins/your-plugin/includes/
 *
 * A helper to format numbers as locale-aware currency strings.
 *
 * @package BuildByHS
 */

if ( ! function_exists( 'buildbyhs_format_price' ) ) {
    /**
     * Format a number into a locale-aware currency string.
     *
     * @param float       $amount           The numeric amount to format.
     * @param string      $currency_symbol  (Optional) Currency symbol to prepend. Default '$'.
     * @param int         $decimals         (Optional) Number of decimal places. Default 2.
     * @param bool        $echo             (Optional) If true, echoes the result. Otherwise returns it. Default false.
     *
     * @return string The formatted currency string (or empty string if echoed).
     *
     * @usage
     * // 1. Default formatting (USD, 2 decimals):
     * echo buildbyhs_format_price( 1234.56 );
     * // → "$1,234.56"
     *
     * // 2. With Euro symbol, no decimals:
     * echo buildbyhs_format_price( 99.9, '€', 0 );
     * // → "€100"
     *
     * // 3. Return instead of echo:
     * $price = buildbyhs_format_price( 2500, '£', 2, true );
     * // echoes "£2,500.00" and $price is empty string
     */
    function buildbyhs_format_price( $amount, $currency_symbol = '$', $decimals = 2, $echo = false ) {
        // 1) Ensure we have a float
        $amount = floatval( $amount );

        // 2) Format number according to locale (thousands sep & decimal point)
        $formatted = number_format_i18n( $amount, intval( $decimals ) );

        // 3) Prepend symbol
        $output = $currency_symbol . $formatted;

        // 4) Echo or return
        if ( $echo ) {
            echo esc_html( $output );
            return '';
        }

        return $output;
    }
}
