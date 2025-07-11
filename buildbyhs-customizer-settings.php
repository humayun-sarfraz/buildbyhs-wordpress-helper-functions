<?php
/**
 * File: buildbyhs-customizer-settings.php
 * Function: buildbyhs_register_customizer_settings
 * Description: Adds custom theme options to the WordPress Customizer with sanitization and live preview support.
 *
 * Usage:
 * // In your theme's functions.php or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-customizer-settings.php';
 * add_action( 'customize_register', 'buildbyhs_register_customizer_settings' );
 */

if ( ! function_exists( 'buildbyhs_register_customizer_settings' ) ) {
    /**
     * Register Customizer sections, settings, and controls.
     *
     * @param WP_Customize_Manager $wp_customize Customizer object.
     */
    function buildbyhs_register_customizer_settings( $wp_customize ) {
        // Add a new section
        $wp_customize->add_section( 'buildbyhs_theme_options', array(
            'title'      => __( 'Theme Options', 'buildbyhs' ),
            'priority'   => 30,
        ) );

        // Site Accent Color setting
        $wp_customize->add_setting( 'buildbyhs_accent_color', array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buildbyhs_accent_color_control', array(
            'label'    => __( 'Accent Color', 'buildbyhs' ),
            'section'  => 'buildbyhs_theme_options',
            'settings' => 'buildbyhs_accent_color',
        ) ) );

        // Footer text setting
        $wp_customize->add_setting( 'buildbyhs_footer_text', array(
            'default'           => __( '© All rights reserved.', 'buildbyhs' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'buildbyhs_footer_text_control', array(
            'label'    => __( 'Footer Text', 'buildbyhs' ),
            'section'  => 'buildbyhs_theme_options',
            'settings' => 'buildbyhs_footer_text',
            'type'     => 'text',
        ) );
    }
}
