<?php
/**
 * File: buildbyhs-wpml-string-registration.php
 * Function: buildbyhs_register_wpml_strings, buildbyhs_translate_string
 * Description: Registers theme/plugin strings with WPML for translation and provides a helper to retrieve translated strings.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-string-registration.php';
 * add_action( 'init', 'buildbyhs_register_wpml_strings' );
 *
 * // To retrieve a translated string:
 * echo buildbyhs_translate_string( 'my_unique_key', 'Default text to translate' );
 */

if ( ! function_exists( 'buildbyhs_register_wpml_strings' ) ) {
    /**
     * Register strings with WPML so they appear in the String Translation module.
     */
    function buildbyhs_register_wpml_strings() {
        if ( ! function_exists( 'icl_register_string' ) ) {
            return;
        }

        /**
         * Filterable list of strings to register:
         * array( key => default text )
         */
        $strings = apply_filters( 'buildbyhs_wpml_strings', array(
            'site_cta_text'  => 'Join our newsletter for updates.',
            'footer_disclaimer' => '© All rights reserved.',
        ) );

        foreach ( $strings as $key => $text ) {
            $domain = 'buildbyhs';
            // sanitize key for WPML
            $name = sanitize_key( $key );
            icl_register_string( $domain, $name, $text );
        }
    }
}

if ( ! function_exists( 'buildbyhs_translate_string' ) ) {
    /**
     * Retrieve the translated string from WPML, falling back to default.
     *
     * @param string $key   Unique key matching the registered string.
     * @param string $default Default text if not translated or WPML not active.
     * @return string Translated or default text.
     */
    function buildbyhs_translate_string( $key, $default = '' ) {
        $domain = 'buildbyhs';
        $name   = sanitize_key( $key );
        if ( function_exists( 'icl_t' ) ) {
            $translated = icl_t( $domain, $name, $default );
            return $translated;
        }
        return $default;
    }
}
