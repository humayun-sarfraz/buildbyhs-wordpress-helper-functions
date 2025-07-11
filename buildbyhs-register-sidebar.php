<?php
/**
 * File: buildbyhs-register-sidebar.php
 * Function: buildbyhs_register_sidebar_areas
 * Description: Registers multiple widgetized sidebar areas (widget areas) with sensible defaults,
 *              proper sanitization and translation-ready names and descriptions.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-register-sidebar.php';
 * add_action( 'widgets_init', 'buildbyhs_register_sidebar_areas' );
 */

if ( ! function_exists( 'buildbyhs_register_sidebar_areas' ) ) {
    /**
     * Register custom sidebar widget areas.
     */
    function buildbyhs_register_sidebar_areas() {
        $sidebars = apply_filters( 'buildbyhs_sidebars', array(
            array(
                'id'          => 'sidebar-primary',
                'name'        => __( 'Primary Sidebar', 'buildbyhs' ),
                'description' => __( 'Main sidebar that appears on the right on each page except the front page template', 'buildbyhs' ),
            ),
            array(
                'id'          => 'footer-widgets',
                'name'        => __( 'Footer Widgets', 'buildbyhs' ),
                'description' => __( 'Widgets displayed in the footer area', 'buildbyhs' ),
            ),
        ) );

        foreach ( $sidebars as $sb ) {
            $id          = sanitize_key( $sb['id'] );
            $name        = sanitize_text_field( $sb['name'] );
            $description = sanitize_text_field( $sb['description'] );

            register_sidebar( array(
                'id'            => $id,
                'name'          => $name,
                'description'   => $description,
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
        }
    }
}
