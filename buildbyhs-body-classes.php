<?php
/**
 * File: buildbyhs-body-classes.php
 * Function: buildbyhs_body_classes
 * Description: Adds custom classes to the <body> element based on context (e.g., page template, user role, etc.),
 *              with proper sanitization.
 *
 * Usage:
 * // In your theme's functions.php or a plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-body-classes.php';
 * add_filter( 'body_class', 'buildbyhs_body_classes' );
 */

if ( ! function_exists( 'buildbyhs_body_classes' ) ) {
    /**
     * Append custom classes to the body class array.
     *
     * @param array $classes Current body classes.
     * @return array Modified list of classes.
     */
    function buildbyhs_body_classes( $classes ) {
        // 1) Add page template slug
        if ( is_page() ) {
            $template = get_page_template_slug( get_queried_object_id() );
            if ( $template ) {
                $slug = sanitize_html_class( str_replace( array( '.php', '/' ), '', $template ) );
                $classes[] = 'template-' . $slug;
            }
        }

        // 2) Add user role class if logged in
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            if ( ! empty( $user->roles ) ) {
                foreach ( $user->roles as $role ) {
                    $classes[] = 'role-' . sanitize_html_class( $role );
                }
            }
        } else {
            $classes[] = 'role-guest';
        }

        // 3) Add custom class for mobile vs. desktop via user agent
        if ( wp_is_mobile() ) {
            $classes[] = 'device-mobile';
        } else {
            $classes[] = 'device-desktop';
        }

        // 4) Filterable for further customization
        return apply_filters( 'buildbyhs_custom_body_classes', $classes );
    }
}
