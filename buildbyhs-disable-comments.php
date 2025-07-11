<?php
/**
 * File: buildbyhs-disable-comments.php
 * Functions: buildbyhs_disable_comments, buildbyhs_disable_comments_admin_menu,
 *            buildbyhs_disable_comments_admin_bar, buildbyhs_disable_comments_dashboard
 * Description: Completely disables comment features throughout WordPress for improved performance and security,
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-comments.php';
 * add_action( 'init', 'buildbyhs_disable_comments', 100 );
 * add_action( 'admin_menu', 'buildbyhs_disable_comments_admin_menu' );
 * add_action( 'wp_before_admin_bar_render', 'buildbyhs_disable_comments_admin_bar' );
 * add_action( 'admin_init', 'buildbyhs_disable_comments_dashboard' );
 */

if ( ! function_exists( 'buildbyhs_disable_comments' ) ) {
    /**
     * Disable support for comments and trackbacks in all post types
     */
    function buildbyhs_disable_comments() {
        // Close comments on front-end
        add_filter( 'comments_open', '__return_false', 20, 2 );
        add_filter( 'pings_open', '__return_false', 20, 2 );

        // Hide existing comments
        add_filter( 'comments_array', '__return_empty_array', 10, 2 );

        // Remove comment-reply script
        add_action( 'wp_enqueue_scripts', function() {
            wp_dequeue_script( 'comment-reply' );
        }, 20 );

        // Remove comment support for post types
        foreach ( get_post_types() as $post_type ) {
            if ( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }
}

if ( ! function_exists( 'buildbyhs_disable_comments_admin_menu' ) ) {
    /**
     * Remove comments menu in admin
     */
    function buildbyhs_disable_comments_admin_menu() {
        remove_menu_page( 'edit-comments.php' );
    }
}

if ( ! function_exists( 'buildbyhs_disable_comments_admin_bar' ) ) {
    /**
     * Remove comments link from admin bar
     */
    function buildbyhs_disable_comments_admin_bar() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }
}

if ( ! function_exists( 'buildbyhs_disable_comments_dashboard' ) ) {
    /**
     * Remove recent comments metabox from dashboard
     */
    function buildbyhs_disable_comments_dashboard() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }
}
