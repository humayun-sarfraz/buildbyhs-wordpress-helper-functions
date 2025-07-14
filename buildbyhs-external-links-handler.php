<?php
/**
 * File: buildbyhs-external-links-handler.php
 * Function: buildbyhs_make_external_links_noreferrer
 * Description: Scans post content for external links and adds target="_blank" and rel="noopener noreferrer" to improve security.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-external-links-handler.php';
 * add_filter( 'the_content', 'buildbyhs_make_external_links_noreferrer', 20 );
 */

if ( ! function_exists( 'buildbyhs_make_external_links_noreferrer' ) ) {
    /**
     * Process post content, updating external <a> tags.
     *
     * @param string $content The post content.
     * @return string Modified content with safe external links.
     */
    function buildbyhs_make_external_links_noreferrer( $content ) {
        // Only run on frontend
        if ( is_admin() ) {
            return $content;
        }

        $home_url = parse_url( home_url(), PHP_URL_HOST );
        // Use DOMDocument to safely manipulate links
        libxml_use_internal_errors( true );
        $dom = new DOMDocument;
        // Enforce UTF-8
        $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        libxml_clear_errors();

        $links = $dom->getElementsByTagName( 'a' );
        foreach ( $links as $link ) {
            $href = $link->getAttribute( 'href' );
            if ( ! $href ) {
                continue;
            }
            $parsed = parse_url( $href );
            if ( empty( $parsed['host'] ) ) {
                continue; // not a full URL
            }
            // If external domain
            if ( strcasecmp( $parsed['host'], $home_url ) !== 0 ) {
                // Add attributes
                $link->setAttribute( 'target', '_blank' );
                $existing_rel = $link->getAttribute( 'rel' );
                $rel_values = array_filter( array_map( 'trim', explode( ' ', $existing_rel ) ) );
                $rel_values = array_unique( array_merge( $rel_values, array( 'noopener', 'noreferrer' ) ) );
                $link->setAttribute( 'rel', implode( ' ', $rel_values ) );
            }
        }

        // Return updated HTML
        $html = $dom->saveHTML();
        // Remove the added XML encoding declaration
        return preg_replace( '/^<\?xml.*?\?>/', '', $html );
    }
}
