<?php
/**
 * File: buildbyhs-rest-api-endpoints.php
 * Function: buildbyhs_register_rest_endpoints
 * Description: Simplifies registration of custom WP REST API routes based on a filterable array of endpoint definitions,
 *              ensuring proper sanitization, permission checks, and JSON responses.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-rest-api-endpoints.php';
 * add_action( 'rest_api_init', 'buildbyhs_register_rest_endpoints' );
 */

if ( ! function_exists( 'buildbyhs_register_rest_endpoints' ) ) {
    /**
     * Register multiple REST API endpoints defined via the 'buildbyhs_rest_endpoints' filter.
     */
    function buildbyhs_register_rest_endpoints() {
        // Default empty array; developers add endpoint definitions via this filter
        $endpoints = apply_filters( 'buildbyhs_rest_endpoints', array() );

        if ( empty( $endpoints ) || ! is_array( $endpoints ) ) {
            return;
        }

        foreach ( $endpoints as $ep ) {
            // Validate namespace and route
            $namespace = ! empty( $ep['namespace'] ) ? sanitize_text_field( $ep['namespace'] ) : 'buildbyhs/v1';
            $route     = ! empty( $ep['route'] )     ? ltrim( sanitize_text_field( $ep['route'] ), '/' ) : ''; 
            if ( ! $route ) {
                continue;
            }

            // Methods
            $methods = ! empty( $ep['methods'] ) ? $ep['methods'] : WP_REST_Server::READABLE;

            // Callback
            if ( empty( $ep['callback'] ) || ! is_callable( $ep['callback'] ) ) {
                continue;
            }
            $callback = $ep['callback'];

            // Permission callback
            $permission = ! empty( $ep['permission_callback'] ) && is_callable( $ep['permission_callback'] )
                ? $ep['permission_callback']
                : '__return_true';

            register_rest_route( $namespace, '/' . $route, array(
                'methods'             => $methods,
                'callback'            => $callback,
                'permission_callback' => $permission,
                'args'                => isset( $ep['args'] ) && is_array( $ep['args'] ) ? $ep['args'] : array(),
            ) );
        }
    }
}
