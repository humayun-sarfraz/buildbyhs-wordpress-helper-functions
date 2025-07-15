<?php
/**
 * File: buildbyhs-cdn-image-rewrite.php
 * Function: buildbyhs_rewrite_image_url_to_cdn
 * Description: Rewrites attachment URLs to point to a configured CDN domain, improving asset delivery speed.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-cdn-image-rewrite.php';
 * add_filter( 'wp_get_attachment_url', 'buildbyhs_rewrite_image_url_to_cdn', 10, 2 );
 *
 * // Optionally, configure via filter; for example:
 * add_filter( 'buildbyhs_cdn_domains', function( $domains ) {
 *     $domains = array('cdn.example.com');
 *     return $domains;
 * } );
 */

if ( ! function_exists( 'buildbyhs_rewrite_image_url_to_cdn' ) ) {
    /**
     * Replace WordPress attachment URLs with CDN URLs if they match the site's domain.
     *
     * @param string $url          Original attachment URL.
     * @param int    $attachment_id Attachment ID.
     * @return string Modified URL pointing to CDN if applicable.
     */
    function buildbyhs_rewrite_image_url_to_cdn( $url, $attachment_id ) {
        // Get the site host
        $site_host = parse_url( home_url(), PHP_URL_HOST );
        $parsed = parse_url( $url );
        if ( empty( $parsed['host'] ) || strcasecmp( $parsed['host'], $site_host ) !== 0 ) {
            // Not a local asset, return original
            return $url;
        }

        // Get list of CDN domains (filterable)
        $cdn_domains = apply_filters( 'buildbyhs_cdn_domains', array() );
        if ( empty( $cdn_domains ) || ! is_array( $cdn_domains ) ) {
            return $url;
        }

        // Pick first valid CDN domain
        $cdn_host = sanitize_text_field( $cdn_domains[0] );
        if ( ! $cdn_host ) {
            return $url;
        }

        // Build new URL
        $scheme = isset( $parsed['scheme'] ) ? $parsed['scheme'] : 'https';
        $path   = isset( $parsed['path'] ) ? $parsed['path'] : '';
        $new_url = esc_url_raw( $scheme . '://' . $cdn_host . $path );
        return $new_url;
    }
}
