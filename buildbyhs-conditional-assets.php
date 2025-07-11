<?php
/**
 * File: buildbyhs-conditional-assets.php
 * Function: buildbyhs_enqueue_conditional_assets
 * Description: Enqueues scripts or styles only on specified pages, templates, or conditions,
 *              improving performance by loading assets where needed.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-conditional-assets.php';
 * add_action( 'wp_enqueue_scripts', 'buildbyhs_enqueue_conditional_assets' );
 */

if ( ! function_exists( 'buildbyhs_enqueue_conditional_assets' ) ) {
    /**
     * Conditionally enqueue assets based on context.
     */
    function buildbyhs_enqueue_conditional_assets() {
        // Example: Only enqueue on single 'product' post type
        if ( is_singular( 'product' ) ) {
            wp_enqueue_style(
                'buildbyhs-product-style',
                get_stylesheet_directory_uri() . '/assets/css/product.css',
                array(),
                filemtime( get_stylesheet_directory() . '/assets/css/product.css' )
            );
            wp_enqueue_script(
                'buildbyhs-product-script',
                get_stylesheet_directory_uri() . '/assets/js/product.js',
                array( 'jquery' ),
                filemtime( get_stylesheet_directory() . '/assets/js/product.js' ),
                true
            );
        }

        // Example: Only enqueue on front page
        if ( is_front_page() ) {
            wp_enqueue_style(
                'buildbyhs-home-style',
                get_stylesheet_directory_uri() . '/assets/css/home.css',
                array(),
                filemtime( get_stylesheet_directory() . '/assets/css/home.css' )
            );
        }

        // Example: Only enqueue on pages using a specific template
        if ( is_page_template( 'templates/contact.php' ) ) {
            wp_enqueue_script(
                'buildbyhs-contact-map',
                'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( get_option( 'google_maps_api_key' ) ),
                array(),
                null,
                true
            );
        }
    }
}
