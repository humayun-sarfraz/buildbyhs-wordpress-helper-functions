<?php
/**
 * File: buildbyhs-log-debug.php
 * Function: buildbyhs_log_debug
 * Description: Writes debug messages or arrays to WP_DEBUG_LOG when WP_DEBUG is true,
 *              with proper sanitization and context logging.
 *
 * Usage:
 * // Basic message:
 * buildbyhs_log_debug( 'Something happened' );
 *
 * // With context array:
 * buildbyhs_log_debug( 'Data state', array( 'foo' => 'bar' ) );
 */

if ( ! function_exists( 'buildbyhs_log_debug' ) ) {
    function buildbyhs_log_debug( $message, $context = array() ) {
        // Only log if debugging is enabled
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
            // Timestamp for log entries
            $timestamp = current_time( 'mysql' );

            // Prepare the main message
            if ( is_array( $message ) || is_object( $message ) ) {
                $log_entry = print_r( $message, true );
            } else {
                $log_entry = sanitize_text_field( $message );
            }

            // Write main log entry
            error_log( "[{$timestamp}] {$log_entry}" );

            // Optionally log context
            if ( ! empty( $context ) && ( is_array( $context ) || is_object( $context ) ) ) {
                error_log( "[{$timestamp}] Context: " . print_r( $context, true ) );
            }
        }
    }
}
