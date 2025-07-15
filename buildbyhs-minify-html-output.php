<?php
/**
 * File: buildbyhs-minify-html-output.php
 * Functions: buildbyhs_start_html_minify, buildbyhs_end_html_minify, buildbyhs_minify_html
 * Description: Buffers full page HTML output and minifies it by removing extra whitespace,
 *              comments, and line breaks, improving page load times.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-minify-html-output.php';
 * add_action( 'template_redirect', 'buildbyhs_start_html_minify' );
 * add_action( 'shutdown',          'buildbyhs_end_html_minify', 0 );
 */

if ( ! function_exists( 'buildbyhs_start_html_minify' ) ) {
    /**
     * Start output buffering to capture HTML.
     */
    function buildbyhs_start_html_minify() {
        if ( ! is_admin() && ! wp_doing_ajax() ) {
            ob_start( 'buildbyhs_minify_html' );
        }
    }
}

if ( ! function_exists( 'buildbyhs_end_html_minify' ) ) {
    /**
     * Flush the buffer and send minified HTML.
     */
    function buildbyhs_end_html_minify() {
        if ( ! is_admin() && ! wp_doing_ajax() && ob_get_length() ) {
            ob_end_flush();
        }
    }
}

if ( ! function_exists( 'buildbyhs_minify_html' ) ) {
    /**
     * Callback to minify HTML by removing comments, tabs, and extra whitespace.
     *
     * @param string $html The full HTML output.
     * @return string Minified HTML.
     */
    function buildbyhs_minify_html( $html ) {
        // Remove HTML comments except conditional IE
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        // Remove whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);
        // Collapse multiple whitespace
        $html = preg_replace('/\s{2,}/', ' ', $html);
        return $html;
    }
}
