<?php
/**
 * File: buildbyhs-disable-revisions.php
 * Functions: buildbyhs_disable_revisions, buildbyhs_disable_autosave
 * Description: Disables post revisions and the Heartbeat autosave interval to reduce database bloat and server load.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-revisions.php';
 * add_filter( 'wp_revisions_to_keep',     'buildbyhs_disable_revisions', 10, 2 );
 * add_filter( 'autosave_interval',        'buildbyhs_disable_autosave' );
 */

if ( ! function_exists( 'buildbyhs_disable_revisions' ) ) {
    /**
     * Disable storing any post revisions.
     *
     * @param int   $num  Number of revisions to keep.
     * @param WP_Post $post Post object.
     * @return int Zero to disable revisions.
     */
    function buildbyhs_disable_revisions( $num, $post ) {
        return 0;
    }
}

if ( ! function_exists( 'buildbyhs_disable_autosave' ) ) {
    /**
     * Increase autosave interval to a high value to effectively disable it.
     *
     * @param int $seconds Interval in seconds.
     * @return int New interval in seconds.
     */
    function buildbyhs_disable_autosave( $seconds ) {
        // Set to one hour (3600 seconds)
        return HOUR_IN_SECONDS;
    }
}
