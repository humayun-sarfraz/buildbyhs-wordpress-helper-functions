<?php
/**
 * File: buildbyhs-redirect-after-login.php
 * Function: buildbyhs_redirect_user_after_login
 * Description: Redirects users to custom URLs based on their role immediately after login,
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // 1) Define a role-to-path mapping in wp-config.php or via add_option:
 * //    define( 'BUILDBYHS_ROLE_REDIRECT_MAP', serialize( array(
 * //        'administrator' => '/wp-admin',
 * //        'editor'        => '/editor-dashboard',
 * //        'subscriber'    => '/welcome',
 * //    ) ) );
 *
 * // 2) Hook into login_redirect:
 * add_filter( 'login_redirect', 'buildbyhs_redirect_user_after_login', 10, 3 );
 */

if ( ! function_exists( 'buildbyhs_redirect_user_after_login' ) ) {
    /**
     * Redirect users after login based on role mapping.
     *
     * @param string           $redirect_to           The URL to redirect to.
     * @param string           $requested_redirect_to The requested redirect URL.
     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error otherwise.
     * @return string Redirect URL.
     */
    function buildbyhs_redirect_user_after_login( $redirect_to, $requested_redirect_to, $user ) {
        // Only proceed on successful login
        if ( is_wp_error( $user ) || ! ( $user instanceof WP_User ) ) {
            return esc_url_raw( $redirect_to );
        }

        // Retrieve role-to-path map from constant or fallback to empty array
        $map = array();
        if ( defined( 'BUILDBYHS_ROLE_REDIRECT_MAP' ) ) {
            $raw = BUILDBYHS_ROLE_REDIRECT_MAP;
            $unserialized = @unserialize( $raw );
            if ( is_array( $unserialized ) ) {
                $map = $unserialized;
            }
        }

        // Loop through user roles and redirect if mapping exists
        foreach ( (array) $user->roles as $role ) {
            if ( isset( $map[ $role ] ) ) {
                $path = sanitize_text_field( $map[ $role ] );
                // Build full URL and escape
                return esc_url_raw( site_url( '/' . ltrim( $path, '/' ) ) );
            }
        }

        // Default fallback
        return esc_url_raw( $redirect_to );
    }
}

// Hook into login_redirect
add_filter( 'login_redirect', 'buildbyhs_redirect_user_after_login', 10, 3 );
