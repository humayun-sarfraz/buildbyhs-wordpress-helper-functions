<?php
/**
 * File: buildbyhs-image-optimization.php
 * Function: buildbyhs_responsive_lazy_images
 * Description: Adds srcset and loading="lazy" attributes to <img> tags in content,
 *              improving performance with responsive images and native lazy-loading.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-image-optimization.php';
 * add_filter( 'the_content', 'buildbyhs_responsive_lazy_images', 20 );
 */

if ( ! function_exists( 'buildbyhs_responsive_lazy_images' ) ) {
    /**
     * Process post content and enhance <img> tags with srcset, sizes, and lazy-loading.
     *
     * @param string $content The post content.
     * @return string Modified content.
     */
    function buildbyhs_responsive_lazy_images( $content ) {
        // Only run on frontend
        if ( is_admin() ) {
            return $content;
        }

        // Use DOMDocument to parse content safely
        libxml_use_internal_errors( true );
        $dom = new DOMDocument;
        // Force UTF-8
        $dom->loadHTML( '<?xml encoding="utf-8"?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        libxml_clear_errors();

        $images = $dom->getElementsByTagName( 'img' );
        foreach ( $images as $img ) {
            $src = $img->getAttribute( 'src' );
            if ( ! $src ) {
                continue;
            }
            // Skip external images
            $parsed = parse_url( $src );
            if ( empty( $parsed['host'] ) || untrailingslashit( $parsed['host'] ) !== untrailingslashit( parse_url( home_url(), PHP_URL_HOST ) ) ) {
                // Still add lazy attribute
                $img->setAttribute( 'loading', 'lazy' );
                continue;
            }

            // Get attachment ID from URL
            $attachment_id = attachment_url_to_postid( $src );
            if ( $attachment_id ) {
                // Build responsive srcset
                $srcset = wp_get_attachment_image_srcset( $attachment_id );
                $sizes  = wp_get_attachment_image_sizes( $attachment_id );
                if ( $srcset ) {
                    $img->setAttribute( 'srcset', esc_attr( $srcset ) );
                }
                if ( $sizes ) {
                    $img->setAttribute( 'sizes', esc_attr( $sizes ) );
                }
            }

            // Add native lazy-loading
            $img->setAttribute( 'loading', 'lazy' );
        }

        // Return updated HTML
        $html = $dom->saveHTML();
        // Remove XML declaration
        $html = preg_replace( '/^<\?xml.*?\?>/', '', $html );
        return $html;
    }
}
