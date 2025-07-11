<?php
/**
 * File: buildbyhs-rest-endpoint.php
 * Function: buildbyhs_register_rest_endpoint
 * Description: Simplifies registration of custom REST API routes with namespace, methods, callback,
 *              permission callback, and proper sanitization.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-rest-endpoint.php';
 * add_action( 'rest_api_init', function() {
 *     buildbyhs_register_rest_endpoint( 'buildbyhs/v1', '/greeting', array(
 *         'methods'             => WP_REST_Server::READABLE,
 *         'callback'            => 'buildbyhs_rest_greeting',
 *         'permission_callback' => '__return_true',
 *     ) );
 * } );
 *
 * function buildbyhs_rest_greeting( WP_REST_Request $request ) {
 *     $name = $request->get_param( 'name' );
 *     $name = sanitize_text_field( $name );
 *     return array( 'message' => sprintf( 'Hello, %s!', $name ?: 'Guest' ) );
 * }
 */

if ( ! function_exists( 'buildbyhs_register_rest_endpoint' ) ) {
    /**
     * Wrapper for register_rest_route with argument validation and sanitization.
     *
     * @param string $namespace API namespace (e.g. 'myplugin/v1').
     * @param string $route     Route path (e.g. '/items/(?P<id>\d+)').
     * @param array  $args      Array of args: methods, callback, permission_callback, args (array).
     */
    function buildbyhs_register_rest_endpoint( $namespace, $route, $args ) {
        $ns   = sanitize_key( $namespace );
        $path = sanitize_text_field( $route );

        if ( empty( $ns ) || empty( $path ) || ! is_array( $args ) ) {
            return;
        }

        $methods             = isset( $args['methods'] )             ? $args['methods'] : WP_REST_Server::ALLMETHODS;
        $callback            = isset( $args['callback'] )            && is_callable( $args['callback'] ) ? $args['callback'] : null;
        $permission_callback = isset( $args['permission_callback'] ) && is_callable( $args['permission_callback'] ) ? $args['permission_callback'] : '__return_false';
        $route_args          = isset( $args['args'] ) && is_array( $args['args'] ) ? $args['args'] : array();

        register_rest_route( $ns, $path, array(
            'methods'             => $methods,
            'callback'            => $callback,
            'permission_callback' => $permission_callback,
            'args'                => $route_args,
        ) );
    }
}
