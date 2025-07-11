<?php
/**
 * File: buildbyhs-post-lock.php
 * Functions: buildbyhs_set_post_lock, buildbyhs_check_post_lock
 * Description: Prevents concurrent editing by locking a post when a user opens the editor.
 *              Shows a notice if another user has an active lock.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-post-lock.php';
 */

if ( ! function_exists( 'buildbyhs_set_post_lock' ) ) {
    /**
     * Set or refresh a lock on the current post for the current user.
     */
    function buildbyhs_set_post_lock() {
        global $pagenow, $post;
        // Only on post edit screens
        if ( 'post.php' !== $pagenow || empty( $post->ID ) ) {
            return;
        }

        $post_id = intval( $post->ID );
        $user_id = get_current_user_id();

        // Store lock info in post meta
        update_post_meta( $post_id, 'buildbyhs_lock_user', $user_id );
        update_post_meta( $post_id, 'buildbyhs_lock_time', current_time( 'timestamp' ) );
    }
    add_action( 'admin_init', 'buildbyhs_set_post_lock' );
}

if ( ! function_exists( 'buildbyhs_check_post_lock' ) ) {
    /**
     * Check if another user holds the lock, and display an admin notice if so.
     */
    function buildbyhs_check_post_lock() {
        global $pagenow, $post;
        if ( 'post.php' !== $pagenow || empty( $post->ID ) ) {
            return;
        }

        $post_id = intval( $post->ID );
        $lock_user = intval( get_post_meta( $post_id, 'buildbyhs_lock_user', true ) );
        $lock_time = intval( get_post_meta( $post_id, 'buildbyhs_lock_time', true ) );
        $current_user = get_current_user_id();

        // Expire lock after 5 minutes
        $timeout = 5 * MINUTE_IN_SECONDS;
        if ( $lock_user && $lock_user !== $current_user && ( current_time( 'timestamp' ) - $lock_time ) < $timeout ) {
            $user_info = get_userdata( $lock_user );
            $username = $user_info ? esc_html( $user_info->display_name ) : esc_html__( 'Another user', 'buildbyhs' );
            $message = sprintf(
                /* translators: %s: username */
                __( '%s is currently editing this post. You may overwrite their changes if you continue.', 'buildbyhs' ),
                $username
            );
            echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
        }
    }
    add_action( 'admin_notices', 'buildbyhs_check_post_lock' );
}
