<?php
/**
 * File: buildbyhs-clean-head.php
 * Functions: buildbyhs_remove_head_links, buildbyhs_remove_emoji_support
 * Description: Cleans up <head> output by removing unnecessary links, version info, and disables emoji scripts/styles.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-clean-head.php';
 * add_action( 'init', function() {
 *     buildbyhs_remove_head_links();
 *     buildbyhs_remove_emoji_support();
 * } );
 */

if ( ! function_exists( 'buildbyhs_remove_head_links' ) ) {
    /**
     * Remove unnecessary <head> actions: RSD, Windows Live Writer, index, canonical, shortlink, WP version.
     */
    function buildbyhs_remove_head_links() {
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'parent_post_rel_link', 10 );
        remove_action( 'wp_head', 'start_post_rel_link', 10 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
    }
}

if ( ! function_exists( 'buildbyhs_remove_emoji_support' ) ) {
    /**
     * Disable built-in emoji support to reduce HTTP requests and JS.
     */
    function buildbyhs_remove_emoji_support() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        // Filter out TinyMCE emojis
        add_filter( 'tiny_mce_plugins', function( $plugins ) {
            if ( is_array( $plugins ) ) {
                return array_diff( $plugins, array( 'wpemoji' ) );
            }
            return $plugins;
        } );
        // Remove emoji CDN
        add_filter( 'emoji_svg_url', '__return_false' );
    }
}
