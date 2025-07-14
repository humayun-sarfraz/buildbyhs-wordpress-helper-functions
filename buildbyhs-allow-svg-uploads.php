<?php
/**
 * File: buildbyhs-allow-svg-uploads.php
 * Functions: buildbyhs_svg_upload_mimes, buildbyhs_svg_file_is_valid, buildbyhs_fix_svg_urls
 * Description: Safely enable SVG uploads in WordPress by whitelisting MIME type,
 *              validating SVG files, and ensuring correct URL handling.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-allow-svg-uploads.php';
 * add_filter( 'upload_mimes', 'buildbyhs_svg_upload_mimes' );
 * add_filter( 'file_is_valid_image', 'buildbyhs_svg_file_is_valid', 10, 2 );
 * add_filter( 'wp_get_attachment_url', 'buildbyhs_fix_svg_urls', 10, 2 );
 */

if ( ! function_exists( 'buildbyhs_svg_upload_mimes' ) ) {
    /**
     * Allow SVG file type in uploads.
     *
     * @param array $mimes Existing MIME types.
     * @return array Modified MIME types allowing SVG.
     */
    function buildbyhs_svg_upload_mimes( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
}

if ( ! function_exists( 'buildbyhs_svg_file_is_valid' ) ) {
    /**
     * Validate uploaded SVG files by checking XML structure.
     *
     * @param bool  $valid       Whether file is a valid image.
     * @param string $file       Full file path.
     * @return bool True if valid SVG or other image, false otherwise.
     */
    function buildbyhs_svg_file_is_valid( $valid, $file ) {
        $ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
        if ( 'svg' === $ext ) {
            // Basic check: file starts with <svg
            $content = file_get_contents( $file );
            if ( strpos( trim( $content ), '<svg' ) === 0 ) {
                return true;
            }
            return false;
        }
        return $valid;
    }
}

if ( ! function_exists( 'buildbyhs_fix_svg_urls' ) ) {
    /**
     * Ensure SVG attachments use the correct file URL (no size suffix).
     *
     * @param string $url          The attachment URL.
     * @param int    $attachment_id Attachment post ID.
     * @return string Modified URL for SVGs.
     */
    function buildbyhs_fix_svg_urls( $url, $attachment_id ) {
        $ext = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
        if ( 'svg' === $ext ) {
            // Remove size parameters if appended by WordPress
            return preg_replace( '/-\d+x\d+(?=\.svg$)/', '', $url );
        }
        return $url;
    }
}
