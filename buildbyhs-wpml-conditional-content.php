<?php
/**
 * File: buildbyhs-wpml-conditional-content.php
 * Function: buildbyhs_wpml_language_content_shortcode
 * Description: Provides a shortcode to conditionally display content based on the current WPML language.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-conditional-content.php';
 * // In post or template:
 * echo do_shortcode( '[lang code="en,fr"]This text shows in English or French.' );
 */

if ( ! function_exists( 'buildbyhs_wpml_language_content_shortcode' ) ) {
    /**
     * Shortcode handler to display content only for specified WPML languages.
     *
     * @param array  $atts    Shortcode attributes. 'code' => comma-separated list of language codes.
     * @param string $content Content between shortcode tags.
     * @return string        The content if current language matches, or empty.
     */
    function buildbyhs_wpml_language_content_shortcode( $atts, $content = null ) {
        // Ensure WPML is active
        if ( ! function_exists( 'icl_get_current_language' ) ) {
            return '';  // WPML not active
        }

        $atts = shortcode_atts( array(
            'code' => '',
        ), $atts, 'lang' );

        $codes = array_filter( array_map( 'sanitize_text_field', explode( ',', $atts['code'] ) ) );
        if ( empty( $codes ) ) {
            return ''; // no codes specified
        }

        $current = sanitize_text_field( icl_get_current_language() );

        if ( in_array( $current, $codes, true ) ) {
            // Return content, allow nested shortcodes and safe HTML
            return do_shortcode( $content );
        }

        return '';
    }
    add_shortcode( 'lang', 'buildbyhs_wpml_language_content_shortcode' );
}

//------------------------------------------------------------------------------
// Helper: body class for current language
//------------------------------------------------------------------------------

if ( ! function_exists( 'buildbyhs_wpml_body_class' ) ) {
    /**
     * Append a language-specific class to the <body> tag (e.g., 'lang-en').
     *
     * @param array $classes Existing body classes.
     * @return array Modified classes.
     */
    function buildbyhs_wpml_body_class( $classes ) {
        if ( function_exists( 'icl_get_current_language' ) ) {
            $lang = sanitize_html_class( icl_get_current_language() );
            $classes[] = 'lang-' . $lang;
        }
        return $classes;
    }
    add_filter( 'body_class', 'buildbyhs_wpml_body_class' );
}
