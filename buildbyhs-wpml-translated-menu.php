<?php
/**
 * File: buildbyhs-wpml-translated-menu.php
 * Function: buildbyhs_wpml_translated_nav_menu
 * Description: Displays a nav menu translated to the specified WPML language (or current language).
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-translated-menu.php';
 * // To render the translated menu for location 'primary':
 * buildbyhs_wpml_translated_nav_menu( 'primary' );
 * // Or specify a language code and custom args:
 * buildbyhs_wpml_translated_nav_menu( 'footer', 'fr', array( 'container' => 'div', 'menu_class' => 'footer-menu' ) );
 */

if ( ! function_exists( 'buildbyhs_wpml_translated_nav_menu' ) ) {
    /**
     * Displays a nav menu translated to the given language.
     *
     * @param string $location      Theme menu location slug.
     * @param string $language_code WPML language code (e.g., 'en', 'fr'). Defaults to current.
     * @param array  $args          Additional wp_nav_menu() arguments.
     */
    function buildbyhs_wpml_translated_nav_menu( $location, $language_code = '', $args = array() ) {
        // Determine language
        if ( function_exists( 'icl_get_current_language' ) ) {
            $language_code = $language_code ?: icl_get_current_language();
        }

        // Get menu locations mapping
        $locations = get_nav_menu_locations();
        if ( empty( $locations[ $location ] ) ) {
            return; // no menu assigned
        }
        $menu_id = intval( $locations[ $location ] );

        // Translate menu term if WPML available
        if ( function_exists( 'icl_object_id' ) ) {
            $translated_id = icl_object_id( $menu_id, 'nav_menu', false, $language_code );
            if ( $translated_id ) {
                $menu_id = intval( $translated_id );
            }
        }

        // Build defaults and merge
        $defaults = array(
            'menu'      => $menu_id,
            'container' => 'nav',
        );
        $menu_args = wp_parse_args( $args, $defaults );

        // Render the menu
        wp_nav_menu( $menu_args );
    }
}
