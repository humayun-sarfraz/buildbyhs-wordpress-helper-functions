<?php
/**
 * File: buildbyhs-custom-excerpt-more.php
 * Function: buildbyhs_modify_excerpt_more
 * Description: Filters the excerpt ‘[...]’ string to HTML-safe custom text/button,
 *              with proper sanitization and translation support.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-custom-excerpt-more.php';
 * add_filter( 'excerpt_more', 'buildbyhs_modify_excerpt_more' );
 */

if ( ! function_exists( 'buildbyhs_modify_excerpt_more' ) ) {
    /**
     * Modify the excerpt "more" string.
     *
     * @param string $more Default "more" string.
     * @return string Modified "more" string.
     */
    function buildbyhs_modify_excerpt_more( $more ) {
        global $post;
        $text = esc_html__( 'Continue reading', 'buildbyhs' );
        $url  = esc_url( get_permalink( $post->ID ) );
        return ' &hellip; <a class="read-more" href="' . $url . '">' . $text . '</a>';
    }
}
