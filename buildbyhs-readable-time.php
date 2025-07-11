<?php
/**
 * File: buildbyhs-readable-time.php
 * Location: wp-content/themes/your-theme/inc/ or wp-content/plugins/your-plugin/includes/
 *
 * A helper to convert dates/times into human-readable formats.
 *
 * @package BuildByHS
 */

if ( ! function_exists( 'buildbyhs_readable_time' ) ) {
    /**
     * Convert a date/time into various human-readable formats.
     *
     * @param int|string $time          A UNIX timestamp or any string strtotime() can parse.
     *                                  Defaults to current time if empty or invalid.
     * @param string     $format_type   One of:
     *                                  - 'relative' : "3 hours ago"
     *                                  - 'date'     : e.g. "July 11, 2025"
     *                                  - 'time'     : e.g. "2:30 PM"
     *                                  - 'datetime' : date + time, using WP settings
     *                                  - 'custom'   : use $custom_format below
     * @param string     $custom_format (optional) A PHP date format string, used only if $format_type==='custom'.
     * @param bool       $echo          If true, echoes the result instead of returning it.
     *
     * @return string The formatted time string.
     *
     * @usage
     * // 1. Relative time:
     * echo buildbyhs_readable_time( '2025-07-10 14:00:00', 'relative' );
     *
     * // 2. Date only:
     * echo buildbyhs_readable_time( 1710165600, 'date' );
     *
     * // 3. Time only:
     * echo buildbyhs_readable_time( 'now', 'time' );
     *
     * // 4. Full datetime:
     * echo buildbyhs_readable_time( '', 'datetime' );
     *
     * // 5. Custom format:
     * echo buildbyhs_readable_time( '2025-07-11 08:00:00', 'custom', 'D, M j @ H:i' );
     */
    function buildbyhs_readable_time( $time = '', $format_type = 'relative', $custom_format = '', $echo = false ) {
        // 1) Normalize to UNIX timestamp
        if ( empty( $time ) ) {
            $timestamp = current_time( 'timestamp' );
        } elseif ( is_numeric( $time ) ) {
            $timestamp = (int) $time;
        } else {
            $timestamp = strtotime( $time );
            if ( false === $timestamp ) {
                // fallback to now if parsing failed
                $timestamp = current_time( 'timestamp' );
            }
        }

        // 2) Determine output based on requested format type
        switch ( $format_type ) {

            // Relative: "5 minutes ago", "2 days ago"
            case 'relative':
                $now    = current_time( 'timestamp' );
                $diff   = human_time_diff( $timestamp, $now );
                $output = sprintf(
                    /* translators: %s: time difference, e.g. "5 minutes" */
                    __( '%s ago', 'buildbyhs-text-domain' ),
                    $diff
                );
                break;

            // Date only, per WP Settings → General → Date Format
            case 'date':
                $output = date_i18n( get_option( 'date_format' ), $timestamp );
                break;

            // Time only, per WP Settings → General → Time Format
            case 'time':
                $output = date_i18n( get_option( 'time_format' ), $timestamp );
                break;

            // Date + Time combined
            case 'datetime':
                $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                $output = date_i18n( $format, $timestamp );
                break;

            // Custom PHP date format
            case 'custom':
                if ( ! empty( $custom_format ) ) {
                    $output = date_i18n( $custom_format, $timestamp );
                } else {
                    $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                    $output = date_i18n( $format, $timestamp );
                }
                break;

            // Default fallback: full datetime
            default:
                $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                $output = date_i18n( $format, $timestamp );
                break;
        }

        // 3) Echo or return
        if ( $echo ) {
            echo esc_html( $output );
            return '';
        }

        return $output;
    }
}
