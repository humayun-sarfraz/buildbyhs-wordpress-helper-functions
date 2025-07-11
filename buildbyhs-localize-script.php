<?php
/**
 * File: buildbyhs-localize-script.php
 * Function: buildbyhs_localize_script_data
 * Description: Safely localizes PHP data into a registered script,
 *              providing a sanitized object for use in frontend JavaScript.
 *
 * Usage:
 * // In your theme or plugin main file, after registering/enqueuing your script:
 * require_once get_stylesheet_directory() . '/buildbyhs-localize-script.php';
 * add_action( 'wp_enqueue_scripts', function() {
 *     wp_enqueue_script( 'theme-main', get_stylesheet_directory_uri() . '/assets/js/main.js', array(), filemtime( get_stylesheet_directory() . '/assets/js/main.js' ), true );
 *     buildbyhs_localize_script_data( 'theme-main', 'ThemeData', array(
 *         'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
 *         'nonce'      => wp_create_nonce( 'theme_ajax_nonce' ),
 *         'homeUrl'    => home_url(),
 *         'isLoggedIn' => is_user_logged_in(),
 *     ) );
 * } );
 */

if ( ! function_exists( 'buildbyhs_localize_script_data' ) ) {
    /**
     * Localize an array of data to a JS script safely.
     *
     * @param string $handle       Script handle (registered/enqueued).
     * @param string $object_name  Global JS object name (no quotes).
     * @param array  $data         Associative array of data to pass.
     */
    function buildbyhs_localize_script_data( $handle, $object_name, $data ) {
        // Validate parameters
        $handle      = sanitize_key( $handle );
        $object_name = preg_replace( '/[^A-Za-z0-9_]/', '', $object_name );

        if ( empty( $handle ) || empty( $object_name ) || ! is_array( $data ) ) {
            return;
        }

        // Sanitize data values (basic strings, booleans, numbers)
        $safe = array();
        foreach ( $data as $key => $value ) {
            // Sanitize array key
            $key = sanitize_key( $key );
            // Sanitize based on type
            if ( is_bool( $value ) ) {
                $safe[ $key ] = $value;
            } elseif ( is_int( $value ) || is_float( $value ) ) {
                $safe[ $key ] = $value;
            } else {
                $safe[ $key ] = sanitize_text_field( wp_json_encode( $value ) );
                // Decode back to prevent double encoding in JS
                $safe[ $key ] = json_decode( $safe[ $key ], true );
            }
        }

        // Localize script
        wp_localize_script( $handle, $object_name, $safe );
    }
}
