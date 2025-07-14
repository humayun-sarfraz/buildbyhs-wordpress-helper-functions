<?php
/**
 * File: buildbyhs-limit-login-attempts.php
 * Function: buildbyhs_limit_login_attempts, buildbyhs_record_failed_login, buildbyhs_clear_login_attempts
 * Description: Implements simple login attempt limiting by user/IP using transients to prevent brute-force attacks.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-limit-login-attempts.php';
 * add_filter( 'authenticate', 'buildbyhs_limit_login_attempts', 30, 3 );
 * add_action( 'wp_login_failed', 'buildbyhs_record_failed_login' );
 * add_action( 'wp_login', 'buildbyhs_clear_login_attempts', 10, 2 );
 */

if ( ! function_exists( 'buildbyhs_limit_login_attempts' ) ) {
    /**
     * Check login attempts before authenticating.
     *
     * @param WP_User|WP_Error|null $user     WP_User or WP_Error or null.
     * @param string                $username Username or email.
     * @param string                $password Password.
     * @return WP_User|WP_Error WP_Error if too many attempts or pass-through.
     */
    function buildbyhs_limit_login_attempts( $user, $username, $password ) {
        $max_attempts = apply_filters( 'buildbyhs_max_login_attempts', 5 );
        $lockout_time = apply_filters( 'buildbyhs_login_lockout_time', 15 * MINUTE_IN_SECONDS );

        // Identify by IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'bhs_login_attempts_' . md5( $ip );

        $attempts = intval( get_transient( $key ) );
        if ( $attempts >= $max_attempts ) {
            return new WP_Error(
                'too_many_attempts',
                sprintf(
                    /* translators: %1$d attempts and %2$d minutes */
                    __( 'Too many failed login attempts. Please try again in %2$d minutes.', 'buildbyhs' ),
                    $max_attempts,
                    ceil( $lockout_time / MINUTE_IN_SECONDS )
                )
            );
        }

        return $user;
    }
}

if ( ! function_exists( 'buildbyhs_record_failed_login' ) ) {
    /**
     * Increment the login attempts counter on failure.
     *
     * @param string $username Username that failed.
     */
    function buildbyhs_record_failed_login( $username ) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'bhs_login_attempts_' . md5( $ip );

        $attempts = intval( get_transient( $key ) );
        $attempts++;

        $lockout_time = apply_filters( 'buildbyhs_login_lockout_time', 15 * MINUTE_IN_SECONDS );
        set_transient( $key, $attempts, $lockout_time );
    }
}

if ( ! function_exists( 'buildbyhs_clear_login_attempts' ) ) {
    /**
     * Clear the login attempts counter on successful login.
     *
     * @param string   $user_login Username.
     * @param WP_User  $user       WP_User object.
     */
    function buildbyhs_clear_login_attempts( $user_login, $user ) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'bhs_login_attempts_' . md5( $ip );
        delete_transient( $key );
    }
}
