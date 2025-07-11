<?php
/**
 * File: buildbyhs-maintenance-mode.php
 * Function: buildbyhs_maintenance_mode, buildbyhs_disable_maintenance_mode
 * Description: Puts the site into maintenance mode for non-logged-in users,
 *              showing a customizable notice, and allows bypass for administrators.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-maintenance-mode.php';
 * // Define a constant or filter for custom message:
 * define( 'BUILDBYHS_MAINTENANCE_MESSAGE', 'We’ll be back soon. Site is under maintenance.' );
 * add_action( 'init', 'buildbyhs_maintenance_mode' );
 */

if ( ! function_exists( 'buildbyhs_maintenance_mode' ) ) {
    /**
     * Displays a maintenance message and exits for non-logged-in visitors.
     */
    function buildbyhs_maintenance_mode() {
        if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
            return; // allow admins
        }
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return; // allow AJAX
        }
        $message = defined( 'BUILDBYHS_MAINTENANCE_MESSAGE' )
            ? BUILDBYHS_MAINTENANCE_MESSAGE
            : __( 'Maintenance in progress. Please check back soon.', 'buildbyhs' );
        $message = wp_kses_post( apply_filters( 'buildbyhs_maintenance_message', $message ) );

        wp_die(
            '<div style="text-align:center; font-family:Arial,sans-serif; padding:40px;">' . esc_html( $message ) . '</div>',
            esc_html__( 'Maintenance Mode', 'buildbyhs' ),
            array( 'response' => 503 )
        );
    }
}

if ( ! function_exists( 'buildbyhs_disable_maintenance_mode' ) ) {
    /**
     * Helper to programmatically disable maintenance mode by removing the action.
     */
    function buildbyhs_disable_maintenance_mode() {
        remove_action( 'init', 'buildbyhs_maintenance_mode' );
    }
}
