<?php
/**
 * File: buildbyhs-admin-page.php
 * Functions: buildbyhs_register_admin_page, buildbyhs_render_admin_page
 * Description: Simplifies adding a custom admin menu page with a callback and optional settings sections.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-admin-page.php';
 * add_action( 'admin_menu', function() {
 *     buildbyhs_register_admin_page( array(
 *         'page_title'  => __( 'Custom Settings', 'buildbyhs' ),
 *         'menu_title'  => __( 'Custom Settings', 'buildbyhs' ),
 *         'capability'  => 'manage_options',
 *         'menu_slug'   => 'buildbyhs_custom_settings',
 *         'callback'    => 'buildbyhs_render_admin_page',
 *         'icon_url'    => 'dashicons-admin-generic',
 *         'position'    => 80,
 *     ) );
 * } );
 *
 * // Callback should render content and output settings fields if any:
 * function buildbyhs_render_admin_page() {
 *     echo '<div class="wrap"><h1>' . esc_html( get_admin_page_title() ) . '</h1>'; 
 *     echo '<form method="post" action="options.php">';
 *     settings_fields( 'buildbyhs_custom_settings_group' );
 *     do_settings_sections( 'buildbyhs_custom_settings' );
 *     submit_button();
 *     echo '</form></div>';
 * }
 */

if ( ! function_exists( 'buildbyhs_register_admin_page' ) ) {
    /**
     * Registers a top-level admin menu page.
     *
     * @param array $args {
     *     page_title  (string) Title of the page.
     *     menu_title  (string) Title in the menu.
     *     capability  (string) Minimum capability to access.
     *     menu_slug   (string) Unique slug for the page.
     *     callback    (callable) Function to render page content.
     *     icon_url    (string) Dashicon or URL. Default: ''.
     *     position    (int) Menu position. Default: null (bottom).
     * }
     */
    function buildbyhs_register_admin_page( $args ) {
        $defaults = array(
            'page_title' => '',
            'menu_title' => '',
            'capability' => 'manage_options',
            'menu_slug'  => '',
            'callback'   => '',
            'icon_url'   => '',
            'position'   => null,
        );
        $args = wp_parse_args( $args, $defaults );

        if ( empty( $args['page_title'] ) || empty( $args['menu_title'] ) || empty( $args['menu_slug'] ) || ! is_callable( $args['callback'] ) ) {
            return;
        }

        add_menu_page(
            esc_html( $args['page_title'] ),
            esc_html( $args['menu_title'] ),
            sanitize_key( $args['capability'] ),
            sanitize_key( $args['menu_slug'] ),
            $args['callback'],
            $args['icon_url'],
            $args['position']
        );
    }
}
