<?php
/**
 * File: buildbyhs-human-time.php
 * Function: buildbyhs_human_readable_time
 * Description: Takes a timestamp or date string and returns a localized “time ago” string
 *              (e.g. “3 hours ago”), with proper sanitization and escaping.
 *
 * Usage:
 * // 1) From a UNIX timestamp:
 * echo buildbyhs_human_readable_time( time() - HOUR_IN_SECONDS * 2 ); // “2 hours ago”
 *
 * // 2) From a date string:
 * echo buildbyhs_human_readable_time( '2025-07-10 08:00:00' );       // e.g. “1 day ago”
 *
 * // 3) In a template:
 * printf(
 *     '<span class="posted-time">%s</span>',
 *     esc_html( buildbyhs_human_readable_time( get_post_time( 'U', true ) ) )
 * );
 */

if ( ! function_exists( 'buildbyhs_human_readable_time' ) ) {
    function buildbyhs_human_readable_time( $time ) {
        // 1) Normalize input: allow integers or date strings
        if ( is_numeric( $time ) ) {
            $timestamp = intval( $time );
        } else {
            $safe_time = sanitize_text_field( $time );
            $timestamp = strtotime( $safe_time );
        }

        // 2) Bail if invalid
        if ( empty( $timestamp ) || $timestamp <= 0 ) {
            return '';
        }

        // 3) Calculate human-friendly difference
        $diff = human_time_diff( $timestamp, current_time( 'timestamp' ) );

        // 4) Format with “ago” and escape for output
        /* translators: %s = human-readable time difference */
        $output = sprintf( _x( '%s ago', '%s = time difference', 'buildbyhs' ), $diff );

        return esc_html( $output );
    }
}
