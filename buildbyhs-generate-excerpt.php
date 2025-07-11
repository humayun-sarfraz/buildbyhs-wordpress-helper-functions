<?php
/**
 * File: buildbyhs-generate-excerpt.php
 * Function: buildbyhs_generate_excerpt
 * Description: Generates a word-limited excerpt from text or post content, preserving whole words
 *              and adding an ellipsis, with proper sanitization and escaping.
 *
 * Usage:
 * // 1) From post content, limit to 20 words
 * echo buildbyhs_generate_excerpt( get_the_content(), 20 ); // e.g. “This is the first twenty words…”
 *
 * // 2) From a custom string
 * $html = '<p>Hello <strong>world</strong>! Here is some <em>sample</em> text with HTML.</p>';
 * printf(
 *   '<p>%s</p>',
 *   esc_html( buildbyhs_generate_excerpt( $html, 10 ) )
 * );
 */

if ( ! function_exists( 'buildbyhs_generate_excerpt' ) ) {
    function buildbyhs_generate_excerpt( $text, $length = 55, $more = '…' ) {
        // 1) Remove all HTML tags
        $clean = wp_strip_all_tags( $text );

        // 2) Sanitize leftover text
        $clean = sanitize_text_field( $clean );

        // 3) Trim to the specified number of words
        $excerpt = wp_trim_words( $clean, intval( $length ), $more );

        // 4) Escape for safe output
        return esc_html( $excerpt );
    }
}
