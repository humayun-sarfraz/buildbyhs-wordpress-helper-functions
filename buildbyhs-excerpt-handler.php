<?php
/**
 * File: buildbyhs-excerpt-handler.php
 * Functions: buildbyhs_custom_excerpt_length, buildbyhs_custom_excerpt_more
 * Description: Adjusts the excerpt length and "read more" text for theme excerpts,
 *              with filterable word count and link text.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-excerpt-handler.php';
 * add_filter( 'excerpt_length', 'buildbyhs_custom_excerpt_length', 999 );
 * add_filter( 'excerpt_more',   'buildbyhs_custom_excerpt_more' );
 */

if ( ! function_exists( 'buildbyhs_custom_excerpt_length' ) ) {
    /**
     * Modify the excerpt length (in words).
     *
     * @param int $length Current excerpt length.
     * @return int New excerpt length.
     */
    function buildbyhs_custom_excerpt_length( $length ) {
        $default = 55;
        $new_length = apply_filters( 'buildbyhs_excerpt_length', $default );
        return intval( $new_length );
    }
}

if ( ! function_exists( 'buildbyhs_custom_excerpt_more' ) ) {
    /**
     * Modify the excerpt "more" string by appending a link.
     *
     * @param string $more Current more string.
     * @return string Modified more string.
     */
    function buildbyhs_custom_excerpt_more( $more ) {
        global $post;
        $text   = apply_filters( 'buildbyhs_excerpt_more_text', __( 'Continue reading', 'buildbyhs' ) );
        $url    = esc_url( get_permalink( $post->ID ) );
        $new    = ' &hellip; <a class="read-more" href="' . $url . '">' . esc_html( $text ) . '</a>';
        return $new;
    }
}
