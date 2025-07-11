<?php
/**
 * File: buildbyhs-wpml-get-translated-id.php
 * Function: buildbyhs_get_translated_post_id, buildbyhs_translate_permalink
 * Description: Helpers to fetch a post's translated ID and URL for a given WPML language code.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-get-translated-id.php';
 *
 * // Get translated post ID:
 * $translated_id = buildbyhs_get_translated_post_id( $post_id, 'fr' );
 *
 * // Get translated permalink or fallback:
 * $url = buildbyhs_translate_permalink( $post_id, 'fr' );
 */

if ( ! function_exists( 'buildbyhs_get_translated_post_id' ) ) {
    /**
     * Retrieve the translated post ID for a given language code.
     *
     * @param int    $post_id     Original post ID.
     * @param string $language_code WPML language code (e.g., 'en', 'fr').
     * @return int|null Translated post ID, or null if not found or WPML inactive.
     */
    function buildbyhs_get_translated_post_id( $post_id, $language_code ) {
        if ( ! function_exists( 'icl_object_id' ) ) {
            return null;
        }
        $language_code = sanitize_text_field( $language_code );
        $translated = icl_object_id( intval( $post_id ), get_post_type( $post_id ), false, $language_code );
        return $translated ? intval( $translated ) : null;
    }
}

if ( ! function_exists( 'buildbyhs_translate_permalink' ) ) {
    /**
     * Get the permalink for a post in a target language, falling back to original if unavailable.
     *
     * @param int    $post_id       Original post ID.
     * @param string $language_code WPML language code.
     * @return string URL of translated or original post.
     */
    function buildbyhs_translate_permalink( $post_id, $language_code ) {
        $translated_id = buildbyhs_get_translated_post_id( $post_id, $language_code );
        if ( $translated_id ) {
            $url = get_permalink( $translated_id );
        } else {
            $url = get_permalink( $post_id );
        }
        return esc_url( $url );
    }
}
