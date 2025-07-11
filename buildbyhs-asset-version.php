<?php
/**
 * File: buildbyhs-asset-version.php
 * Function: buildbyhs_asset_version
 * Description: Appends a file-mtime-based version query string to asset URLs for automatic cache busting.
 *
 * Usage:
 * // Enqueue a stylesheet with cache busting:
 * wp_enqueue_style( 'theme-main', buildbyhs_asset_version( 'assets/css/main.css' ) );
 *
 * // Enqueue a script with cache busting:
 * wp_enqueue_script( 'theme-scripts', buildbyhs_asset_version( 'assets/js/app.js' ), array(), null, true );
 */

if ( ! function_exists( 'buildbyhs_asset_version' ) ) {
    function buildbyhs_asset_version( $relative_path ) {
        // 1) Sanitize input: ensure no leading slash and safe text
        $relative_path = ltrim( sanitize_text_field( $relative_path ), '/' );

        // 2) Determine full server path
        $full_path = get_stylesheet_directory() . '/' . $relative_path;

        // 3) Generate version: filemtime if exists, otherwise fallback to theme version
        if ( file_exists( $full_path ) ) {
            $ver = filemtime( $full_path );
        } else {
            $ver = wp_get_theme()->get( 'Version' );
        }

        // 4) Build URL and append version query arg
        $base_url = get_stylesheet_directory_uri() . '/' . $relative_path;
        $versioned_url = add_query_arg( 'ver', $ver, $base_url );

        // 5) Return safely-escaped URL
        return esc_url( $versioned_url );
    }
}
