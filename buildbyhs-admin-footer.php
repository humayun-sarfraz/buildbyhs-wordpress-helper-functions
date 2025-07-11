<?php
/**
 * File: buildbyhs-admin-footer.php
 * Function: buildbyhs_customize_admin_footer
 * Description: Replaces the default admin footer text with custom branding or links,
 *              properly escaped and translatable.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-admin-footer.php';
 * add_filter( 'admin_footer_text', 'buildbyhs_customize_admin_footer' );
 */

if ( ! function_exists( 'buildbyhs_customize_admin_footer' ) ) {
    /**
     * Customize the admin dashboard footer text.
     *
     * @param string $footer_text The default footer text.
     * @return string Modified footer text.
     */
    function buildbyhs_customize_admin_footer( $footer_text ) {
        $custom  = sprintf(
            /* translators: %s: link to site */
            __( 'Powered by <a href="%s" target="_blank">%s</a>', 'buildbyhs' ),
            esc_url( home_url() ),
            esc_html( get_bloginfo( 'name' ) )
        );
        return $custom;
    }
}
