<?php
/**
 * File: buildbyhs-robots-txt.php
 * Function: buildbyhs_generate_robots_txt
 * Description: Generates a dynamic robots.txt output, allowing customizable rules
 *              via filters, with proper sanitization and escaping.
 *
 * Usage:
 * // In your theme or plugin:
 * add_filter( 'pre_option_robots_txt', 'buildbyhs_generate_robots_txt' );
 */

if ( ! function_exists( 'buildbyhs_generate_robots_txt' ) ) {
    /**
     * Filter callback to generate robots.txt content.
     *
     * @param string|false $output Existing robots.txt or false.
     * @return string Robots.txt directives.
     */
    function buildbyhs_generate_robots_txt( $output ) {
        // Default directives
        $lines = array(
            'User-agent: *',
            'Disallow: /wp-admin/',
            'Allow: /wp-admin/admin-ajax.php',
            '',
            'Sitemap: ' . esc_url( home_url( '/?sitemap=xml' ) ),
        );

        /**
         * Allow developers to modify or extend robots.txt lines.
         *
         * @param array $lines Array of lines.
         */
        $lines = apply_filters( 'buildbyhs_robots_lines', $lines );

        // Sanitize and join lines
        $safe_lines = array_map( 'sanitize_text_field', $lines );
        return implode( "\n", $safe_lines );
    }
}
