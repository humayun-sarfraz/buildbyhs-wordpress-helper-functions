<?php
/**
 * File: buildbyhs-admin-assets.php
 * Function: buildbyhs_enqueue_admin_assets
 * Description: Enqueues custom admin CSS and JavaScript only on specified admin screens,
 *              reducing load and allowing screen-specific enhancements with sanitization.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-admin-assets.php';
 * add_action( 'admin_enqueue_scripts', 'buildbyhs_enqueue_admin_assets' );
 */

if ( ! function_exists( 'buildbyhs_enqueue_admin_assets' ) ) {
    /**
     * Enqueue CSS/JS in admin conditionally based on screen.
     *
     * @param string $hook_suffix Current admin page hook suffix.
     */
    function buildbyhs_enqueue_admin_assets( $hook_suffix ) {
        // Example: Only on post edit screens
        if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
            wp_enqueue_style(
                'buildbyhs-admin-post-style',
                get_stylesheet_directory_uri() . '/assets/css/admin-post.css',
                array(),
                filemtime( get_stylesheet_directory() . '/assets/css/admin-post.css' )
            );
            wp_enqueue_script(
                'buildbyhs-admin-post-script',
                get_stylesheet_directory_uri() . '/assets/js/admin-post.js',
                array( 'jquery' ),
                filemtime( get_stylesheet_directory() . '/assets/js/admin-post.js' ),
                true
            );
        }

        // Example: Only on dashboard
        if ( 'index.php' === $hook_suffix ) {
            wp_enqueue_style(
                'buildbyhs-admin-dashboard-style',
                get_stylesheet_directory_uri() . '/assets/css/admin-dashboard.css',
                array(),
                filemtime( get_stylesheet_directory() . '/assets/css/admin-dashboard.css' )
            );
        }
    }
}
