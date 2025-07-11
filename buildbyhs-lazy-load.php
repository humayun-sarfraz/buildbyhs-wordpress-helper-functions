<?php
/**
 * File: buildbyhs-lazy-load.php
 * Function: buildbyhs_lazy_load_images
 * Description: Filters image attributes to add native lazy-loading,
 *              ensuring proper sanitization and escaping.
 *
 * Usage:
 * // Enable for all attachment images:
 * add_filter( 'wp_get_attachment_image_attributes', 'buildbyhs_lazy_load_images', 10, 3 );
 *
 * // Or apply manually:
 * $attrs = array( 'src' => esc_url( wp_get_attachment_url( $attachment_id ) ) );
 * $lazy_attrs = buildbyhs_lazy_load_images( $attrs, $attachment_id, 'full' );
 * printf( '<img %s />', buildbyhs_attr_string( $lazy_attrs ) );
 */

if ( ! function_exists( 'buildbyhs_lazy_load_images' ) ) {
    function buildbyhs_lazy_load_images( $attrs, $attachment, $size ) {
        // Ensure attributes is an array
        if ( ! is_array( $attrs ) ) {
            return $attrs;
        }

        // 1) Add loading attribute
        $attrs['loading'] = 'lazy';

        // 2) Sanitize URL attributes
        if ( ! empty( $attrs['src'] ) ) {
            $attrs['src'] = esc_url( $attrs['src'] );
        }
        if ( ! empty( $attrs['srcset'] ) ) {
            $attrs['srcset'] = esc_attr( $attrs['srcset'] );
        }

        // 3) Sanitize any other attribute values
        foreach ( $attrs as $key => $value ) {
            if ( in_array( $key, array( 'src', 'srcset', 'loading' ), true ) ) {
                continue;
            }
            $attrs[ $key ] = sanitize_text_field( $value );
        }

        return $attrs;
    }
}
