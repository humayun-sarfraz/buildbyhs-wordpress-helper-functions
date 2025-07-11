<?php
/**
 * File: buildbyhs-schedule-event.php
 * Function: buildbyhs_schedule_recurrence, buildbyhs_clear_scheduled_event
 * Description: Simplifies scheduling and clearing recurring WP Cron events with custom intervals,
 *              ensuring proper sanitization and checking for duplicates.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-schedule-event.php';
 *
 * // 1) Add custom interval (if needed) on init:
 * add_filter( 'cron_schedules', function( $schedules ) {
 *     $schedules['weekly'] = array(
 *         'interval' => WEEK_IN_SECONDS,
 *         'display'  => __( 'Once Weekly', 'buildbyhs' ),
 *     );
 *     return $schedules;
 * } );
 *
 * // 2) Schedule an event:
 * buildbyhs_schedule_recurrence( 'buildbyhs_weekly_task', 'weekly', 'buildbyhs_do_weekly_task' );
 *
 * // 3) Hook your callback:
 * add_action( 'buildbyhs_do_weekly_task', 'my_weekly_task_callback' );
 *
 * // 4) Clear scheduled event (if needed):
 * // buildbyhs_clear_scheduled_event( 'buildbyhs_weekly_task' );
 */

if ( ! function_exists( 'buildbyhs_schedule_recurrence' ) ) {
    /**
     * Schedule a recurring event if not already scheduled.
     *
     * @param string $hook        Action hook name to fire.
     * @param string $recurrence  Recurrence key from cron_schedules.
     * @param string $callback    Callback function name (optional) to hook immediately.
     */
    function buildbyhs_schedule_recurrence( $hook, $recurrence, $callback = '' ) {
        $hook = sanitize_key( $hook );
        $recurrence = sanitize_key( $recurrence );

        // Schedule if not exists
        if ( ! wp_next_scheduled( $hook ) ) {
            wp_schedule_event( time(), $recurrence, $hook );
        }

        // Optionally add the callback if provided
        if ( $callback && is_callable( $callback ) ) {
            add_action( $hook, $callback );
        }
    }
}

if ( ! function_exists( 'buildbyhs_clear_scheduled_event' ) ) {
    /**
     * Clear all scheduled instances of a hook.
     *
     * @param string $hook Action hook name.
     */
    function buildbyhs_clear_scheduled_event( $hook ) {
        $hook = sanitize_key( $hook );
        $timestamp = wp_next_scheduled( $hook );
        while ( $timestamp ) {
            wp_unschedule_event( $timestamp, $hook );
            $timestamp = wp_next_scheduled( $hook );
        }
    }
}
