<?php
/**
 * File: buildbyhs-inline-svg.php
 * Function: buildbyhs_inline_svg_sprite
 * Description: Fetches an SVG file from the theme or plugin directory and outputs it inline,
 *              allowing for CSS styling and accessibility, with proper sanitization and escaping.
 *
 * Usage:
 * // 1) Basic inline SVG from theme's assets/icons folder:
 * echo buildbyhs_inline_svg_sprite( 'assets/icons/logo.svg', 'icon-logo' );
 *
 * // 2) With custom class for styling:
 * printf(
 *   '<div class="icon-wrapper">%s</div>',
 *   buildbyhs_inline_svg_sprite( 'assets/icons/menu.svg', 'icon-menu size-large' )
 * );
 */

if ( ! function_exists( 'buildbyhs_inline_svg_sprite' ) ) {
    function buildbyhs_inline_svg_sprite( $relative_path, $class = '' ) {
        // 1) Sanitize inputs
        $relative_path = sanitize_text_field( $relative_path );
        $class         = sanitize_html_class( $class );

        // 2) Determine full file path
        $base_dir = get_stylesheet_directory();
        $file     = $base_dir . '/' . ltrim( $relative_path, '/' );

        // 3) Validate file existence and extension
        if ( ! file_exists( $file ) || strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) !== 'svg' ) {
            return ''; // File missing or not an SVG
        }

        // 4) Retrieve SVG content
        $svg = file_get_contents( $file );
        if ( ! $svg ) {
            return '';
        }

        // 5) Remove potential scripts and inline event handlers
        $svg = preg_replace( '/<script.*?>.*?<\/script>/si', '', $svg );
        $svg = preg_replace( '/\son\w+=".*?"/si', '', $svg );

        // 6) Return inline SVG wrapped in a sanitized span
        return '<span class="' . esc_attr( $class ) . '">' . $svg . '</span>';
    }
}
