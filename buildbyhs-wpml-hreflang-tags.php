<?php
/**
 * File: buildbyhs-wpml-hreflang-tags.php
 * Function: buildbyhs_wpml_output_hreflang_tags
 * Description: Outputs <link rel="alternate" hreflang="..." /> tags for all active WPML languages.
 *
 * Usage:
 * // In your theme header.php within <head>:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-hreflang-tags.php';
 * add_action( 'wp_head', 'buildbyhs_wpml_output_hreflang_tags', 1 );
 */

if ( ! function_exists( 'buildbyhs_wpml_output_hreflang_tags' ) ) {
    /**
     * Echo hreflang tags for WPML languages on the current URL.
     */
    function buildbyhs_wpml_output_hreflang_tags() {
        if ( ! function_exists( 'icl_get_languages' ) ) {
            return;
        }

        $languages = icl_get_languages('skip_missing=0');
        if ( empty( $languages ) ) {
            return;
        }

        foreach ( $languages as $lang ) {
            if ( ! empty( $lang['url'] ) && ! empty( $lang['language_code'] ) ) {
                $url  = esc_url( $lang['url'] );
                $code = esc_attr( $lang['language_code'] );
                echo "<link rel=\"alternate\" hreflang=\"{$code}\" href=\"{$url}\" />\n";
            }
        }

        // x-default tag
        if ( isset( $languages['en'] ) ) {
            $default_url = esc_url( $languages['en']['url'] );
        } else {
            // pick first language
            $first = reset( $languages );
            $default_url = esc_url( $first['url'] );
        }
        echo "<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$default_url}\" />\n";
    }
}
