<?php
/**
 * File: buildbyhs-wpml-language-logo.php
 * Function: buildbyhs_wpml_language_logo
 * Description: Outputs a language-specific logo image based on the current WPML language,
 *              falling back to a default logo if no match is found.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-language-logo.php';
 *
 * // Define an array of logos (language_code => URL), plus a default:
 * $logos = array(
 *     'default' => get_stylesheet_directory_uri() . '/assets/images/logo-default.png',
 *     'en'      => get_stylesheet_directory_uri() . '/assets/images/logo-en.png',
 *     'fr'      => get_stylesheet_directory_uri() . '/assets/images/logo-fr.png',
 * );
 *
 * // In your header template:
 * buildbyhs_wpml_language_logo( $logos, get_bloginfo( 'name' ) );
 */

if ( ! function_exists( 'buildbyhs_wpml_language_logo' ) ) {
    /**
     * Echoes an <img> tag for the logo corresponding to the current WPML language.
     *
     * @param array  $logos Associative array of language_code => logo URL. Include 'default' key.
     * @param string $alt   Alt text for the logo image.
     */
    function buildbyhs_wpml_language_logo( $logos = array(), $alt = '' ) {
        // Determine current language or empty
        $lang = function_exists( 'icl_get_current_language' ) ? icl_get_current_language() : '';

        // Select appropriate logo URL
        if ( ! empty( $lang ) && ! empty( $logos[ $lang ] ) ) {
            $logo_url = esc_url( $logos[ $lang ] );
        } elseif ( ! empty( $logos['default'] ) ) {
            $logo_url = esc_url( $logos['default'] );
        } else {
            return; // No logo to display
        }

        // Sanitize alt attribute
        $alt_text = sanitize_text_field( $alt );

        // Output the image tag
        printf(
            '<img src="%1$s" alt="%2$s" class="buildbyhs-logo buildbyhs-logo-%3$s" />',
            $logo_url,
            $alt_text,
            esc_attr( $lang ?: 'default' )
        );
    }
}
