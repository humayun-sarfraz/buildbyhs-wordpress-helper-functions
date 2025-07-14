<?php
/**
 * File: buildbyhs-login-redirect.php
 * Function: buildbyhs_login_redirect_by_role
 * Description: Redirect users to different destinations after login based on their role,
 *              with a default fallback and filterable URLs.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-login-redirect.php';
 * add_filter( 'login_redirect', 'buildbyhs_login_redirect_by_role', 10, 3 );
 */

if ( ! function_exists( 'buildbyhs_login_redirect_by_role' ) ) {
    /**
     * Redirect users after login based on their primary role.
     *
     * @param string           $redirect_to           Requested redirect destination.
     * @param string           $requested_redirect_to Default redirect.
     * @param WP_User|WP_Error $user                  WP_User object if login successful, WP_Error otherwise.
     * @return string Redirect URL.
     */
    function buildbyhs_login_redirect_by_role( $redirect_to, $requested_redirect_to, $user ) {
        // If error or no user, handshake to default
        if ( is_wp_error( $user ) || ! $user instanceof WP_User ) {
            return $redirect_to;
        }

        // Get primary role
        $roles = $user->roles;
        $role  = array_shift( $roles );

        // Define default destinations per role
        $destinations = apply_filters( 'buildbyhs_login_redirect_destinations', array(
            'administrator' => admin_url(),          // send admins to dashboard
            'editor'        => admin_url('edit.php'), // send editors to posts
            'author'        => admin_url('edit.php?post_type=post'),
            'subscriber'    => home_url('/profile/'), // assume a profile page exists
        ) );

        // Choose URL: specific role or fallback
        if ( ! empty( $destinations[ $role ] ) ) {
            $url = $destinations[ $role ];
        } else {
            // Fallback to homepage or requested
            $url = $requested_redirect_to ? $requested_redirect_to : home_url();
        }

        // Sanitize and return
        return esc_url_raw( $url );
    }
}
