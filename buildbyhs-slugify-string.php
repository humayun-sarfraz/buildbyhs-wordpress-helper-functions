<?php
/**
 * File: buildbyhs-slugify-string.php
 * Function: buildbyhs_slugify_string
 * Description: Converts any given string into a URL-safe “slug” (lowercase, hyphens, stripped of diacritics),
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // Raw input:
 * $raw = "Hello, World! This is Ünicode.";
 * // Generate slug:
 * $slug = buildbyhs_slugify_string( $raw ); // "hello-world-this-is-unicode"
 * // Output safely in HTML:
 * echo esc_html( $slug );
 */

if ( ! function_exists( 'buildbyhs_slugify_string' ) ) {
    function buildbyhs_slugify_string( $string ) {
        // 1) Sanitize the raw input
        $safe_string = sanitize_text_field( $string );

        // 2) Generate a URL-safe slug
        $slug = sanitize_title( $safe_string );

        // 3) Return the slug (already safe for use in URLs/HTML attributes)
        return $slug;
    }
}
