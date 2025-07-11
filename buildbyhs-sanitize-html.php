<?php
/**
 * File: buildbyhs-sanitize-html.php
 * Function: buildbyhs_sanitize_html
 * Description: Sanitizes a block of HTML content by applying a whitelist of allowed tags and attributes,
 *              with optional customization via filter, ensuring safe output.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-sanitize-html.php';
 *
 * // Sanitize default allowed tags:
 * echo buildbyhs_sanitize_html( '<p><a href="https://example.com" onclick="alert(1)">Link</a></p>' );
 *
 * // Customize allowed tags via filter:
 * add_filter( 'buildbyhs_allowed_html_tags', function( $tags ) {
 *     $tags['iframe'] = array(
 *         'src'             => true,
 *         'width'           => true,
 *         'height'          => true,
 *         'frameborder'     => true,
 *         'allowfullscreen' => true,
 *     );
 *     return $tags;
 * } );
 */

if ( ! function_exists( 'buildbyhs_sanitize_html' ) ) {
    /**
     * Sanitize HTML using a configurable whitelist.
     *
     * @param string $html         The HTML string to sanitize.
     * @param array  $allowed_tags Optional array of allowed tags/attributes (same format as wp_kses).
     * @return string Sanitized HTML string.
     */
    function buildbyhs_sanitize_html( $html, $allowed_tags = null ) {
        // Default allowed tags and attributes
        $default_tags = array(
            'a'      => array('href' => true, 'title' => true, 'target' => true, 'rel' => true),
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'p'      => array(),
            'ul'     => array(),
            'ol'     => array(),
            'li'     => array(),
            'blockquote' => array('cite' => true),
            'code'   => array(),
            'pre'    => array(),
            'img'    => array('src' => true, 'alt' => true, 'width' => true, 'height' => true),
            'span'   => array('class' => true),
            'div'    => array('class' => true),
        );

        /**
         * Filter to customize the allowed HTML tags and attributes.
         *
         * @param array $tags Associative array of tags => attributes.
         */
        $allowed = apply_filters( 'buildbyhs_allowed_html_tags', $default_tags );

        // If specific tags passed in, merge them
        if ( is_array( $allowed_tags ) ) {
            $allowed = array_merge( $allowed, $allowed_tags );
        }

        // Sanitize using wp_kses
        return wp_kses( $html, $allowed );
    }
}
