<?php
/**
 * File: buildbyhs-cleanup-head.php
 * Function: buildbyhs_cleanup_head
 * Description: Removes unnecessary tags and scripts from the <head> to streamline output and improve performance.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-cleanup-head.php';
 * add_action( 'init', 'buildbyhs_cleanup_head' );
 */

if ( ! function_exists( 'buildbyhs_cleanup_head' ) ) {
    /**
     * Clean up default WP head actions.
     */
    function buildbyhs_cleanup_head() {
        // Remove RSS feed links
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );

        // Remove EditURI/RSD link
        remove_action( 'wp_head', 'rsd_link' );

        // Remove Windows Live Writer manifest
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // Remove index link
        remove_action( 'wp_head', 'index_rel_link' );

        // Remove previous/next post relational links
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

        // Remove WordPress version
        remove_action( 'wp_head', 'wp_generator' );

        // Remove shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );

        // Disable emoji scripts and styles
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

        // Remove REST API link from head
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'template_redirect', 'rest_output_link_header', 11 );

        // Remove oEmbed discovery links
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

        // Remove wp resource hints (preconnect)
        remove_action( 'wp_head', 'wp_resource_hints', 2 );

        // Disable WordPress REST API if desired
        // add_filter( 'rest_authentication_errors', '__return_true' );
    }
}
