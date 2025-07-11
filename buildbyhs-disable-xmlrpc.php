<?php
/**
 * File: buildbyhs-disable-xmlrpc.php
 * Function: buildbyhs_disable_xmlrpc
 * Description: Completely disables the XML-RPC API in WordPress to improve security and performance.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-xmlrpc.php';
 * add_filter( 'xmlrpc_enabled', 'buildbyhs_disable_xmlrpc' );
 */

if ( ! function_exists( 'buildbyhs_disable_xmlrpc' ) ) {
    /**
     * Disable XML-RPC functionality.
     *
     * @return bool Always false to disable xmlrpc.
     */
    function buildbyhs_disable_xmlrpc() {
        return false;
    }
}

// Additionally remove legacy endpoints and filters
add_filter( 'wp_headers', function( $headers ) {
    // Remove the X-Pingback header
    if ( isset( $headers['X-Pingback'] ) ) {
        unset( $headers['X-Pingback'] );
    }
    return $headers;
} );

remove_action( 'rest_api_init', 'wp_oembed_register_route' );
add_filter( 'rest_enabled', '__return_false' );
add_filter( 'rest_jsonp_enabled', '__return_false' );
