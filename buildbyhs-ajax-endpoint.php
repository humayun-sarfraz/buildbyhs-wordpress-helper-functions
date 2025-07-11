<?php
/**
 * File: buildbyhs-ajax-endpoint.php
 * Function: buildbyhs_register_ajax_endpoints
 * Description: Simplifies registering WordPress AJAX endpoints with proper sanitization,
 *              nonce checking structure, and standardized JSON responses.
 *
 * Usage:
 * // 1) Define callbacks elsewhere in your plugin or theme:
 * function my_custom_action_callback() {
 *     // Verify nonce
 *     check_ajax_referer( 'my_custom_action_nonce', 'nonce' );
 *
 *     // Sanitize input
 *     $data = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';
 *
 *     // Process and return JSON
 *     if ( empty( $data ) ) {
 *         wp_send_json_error( [ 'message' => 'No data provided.' ] );
 *     }
 *     wp_send_json_success( [ 'received' => $data ] );
 * }
 *
 * // 2) Register endpoints on init:
 * add_action( 'init', function() {
 *     buildbyhs_register_ajax_endpoints( [
 *         [
 *             'action'   => 'my_custom_action',
 *             'callback' => 'my_custom_action_callback',
 *             'nopriv'   => true,               // allow non-logged-in users
 *             'nonce'    => 'my_custom_action_nonce', // optional: auto-check nonce
 *         ],
 *     ] );
 * } );
 */

if ( ! function_exists( 'buildbyhs_register_ajax_endpoints' ) ) {
    /**
     * Registers multiple AJAX endpoints.
     *
     * @param array $endpoints List of endpoint definitions:
     *                         [
     *                           'action'   => (string) AJAX action name,
     *                           'callback' => (callable) handler function,
     *                           'nopriv'   => (bool) allow unauthenticated,
     *                           'nonce'    => (string|null) nonce action to check,
     *                         ]
     */
    function buildbyhs_register_ajax_endpoints( $endpoints ) {
        if ( empty( $endpoints ) || ! is_array( $endpoints ) ) {
            return;
        }

        foreach ( $endpoints as $ep ) {
            // Validate and sanitize action name
            if ( empty( $ep['action'] ) || ! is_string( $ep['action'] ) ) {
                continue;
            }
            $action = sanitize_key( $ep['action'] );

            // Validate callback
            if ( empty( $ep['callback'] ) || ! is_callable( $ep['callback'] ) ) {
                continue;
            }
            $callback = $ep['callback'];

            $nopriv = ! empty( $ep['nopriv'] );

            // Register for logged-in users
            add_action( "wp_ajax_{$action}", function() use ( $callback, $ep ) {
                // Optional nonce check
                if ( ! empty( $ep['nonce'] ) ) {
                    check_ajax_referer( sanitize_key( $ep['nonce'] ), 'nonce' );
                }
                call_user_func( $callback );
            } );

            // Register for guests if requested
            if ( $nopriv ) {
                add_action( "wp_ajax_nopriv_{$action}", function() use ( $callback, $ep ) {
                    if ( ! empty( $ep['nonce'] ) ) {
                        check_ajax_referer( sanitize_key( $ep['nonce'] ), 'nonce' );
                    }
                    call_user_func( $callback );
                } );
            }
        }
    }
}
