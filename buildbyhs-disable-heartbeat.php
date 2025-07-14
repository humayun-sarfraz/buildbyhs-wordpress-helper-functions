<?php
/**
 * File: buildbyhs-disable-heartbeat.php
 * Function: buildbyhs_control_heartbeat
 * Description: Limits or disables the WordPress Heartbeat API to reduce server load,
 *              allowing customization per screen or entirely.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-heartbeat.php';
 * add_filter( 'heartbeat_settings', 'buildbyhs_control_heartbeat' );
 */

if ( ! function_exists( 'buildbyhs_control_heartbeat' ) ) {
    /**
     * Adjusts Heartbeat heartbeat tick interval or disables it based on location.
     *
     * @param array $settings Existing heartbeat settings: interval in seconds.
     * @return array Modified settings.
     */
    function buildbyhs_control_heartbeat( $settings ) {
        $interval = apply_filters( 'buildbyhs_heartbeat_interval', 60 );
        $disable_on = apply_filters( 'buildbyhs_heartbeat_disable_on', array( 'front' ) );

        // Disable on front-end if requested
        if ( ( in_array( 'front', $disable_on, true ) && ! is_admin() )
            || ( in_array( 'admin', $disable_on, true ) && is_admin() ) ) {
            // A zero interval disables the API
            $settings['interval'] = 0;
        } else {
            $settings['interval'] = intval( $interval );
        }

        return $settings;
    }
}
