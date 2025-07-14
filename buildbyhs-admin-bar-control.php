<?php
/**
 * File: buildbyhs-admin-bar-control.php
 * Function: buildbyhs_control_admin_bar
 * Description: Shows or hides the WordPress admin bar based on user role, capability, or URL conditions.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-admin-bar-control.php';
 * add_action( 'after_setup_theme', 'buildbyhs_control_admin_bar' );
 */

if ( ! function_exists( 'buildbyhs_control_admin_bar' ) ) {
    /**
     * Control display of the admin bar.
     *
     * @return void
     */
    function buildbyhs_control_admin_bar() {
        // Determine current user
        if ( ! is_user_logged_in() ) {
            show_admin_bar( false );
            return;
        }

        $user = wp_get_current_user();

        /**
         * Filter to specify roles allowed to see the admin bar.
         * Return an array of role slugs. Default: only 'administrator'.
         * @param array $roles
         */
        $allowed_roles = apply_filters( 'buildbyhs_admin_bar_allowed_roles', array( 'administrator' ) );

        // If user has any allowed role, show it
        foreach ( $user->roles as $role ) {
            if ( in_array( $role, $allowed_roles, true ) ) {
                return; // leave default (true)
            }
        }

        // Hide admin bar for all others
        show_admin_bar( false );
    }
}
