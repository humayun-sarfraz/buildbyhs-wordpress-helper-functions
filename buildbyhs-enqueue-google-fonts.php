<?php
/**
 * File: buildbyhs-enqueue-google-fonts.php
 * Location: wp-content/themes/your-theme/inc/ or wp-content/plugins/your-plugin/includes/
 *
 * A helper to enqueue one or more Google Font families with correct query args.
 *
 * @package BuildByHS
 */

if ( ! function_exists( 'buildbyhs_enqueue_google_fonts' ) ) {
    /**
     * Enqueue one or more Google Font families.
     *
     * @param string|string[] $fonts    One font family string or an array of families.
     *                                  Each may include weights/styles, e.g. 'Poppins:400,700'.
     * @param string          $handle   (Optional) Handle for the stylesheet. Default 'buildbyhs-google-fonts'.
     * @param bool            $return   (Optional) If true, returns the generated URL instead of enqueueing.
     *
     * @return void|string If $return===true, returns the Google Fonts URL; otherwise void.
     *
     * @usage
     * // Enqueue a single font family:
     * buildbyhs_enqueue_google_fonts( 'Poppins:400,700' );
     *
     * // Enqueue multiple families:
     * buildbyhs_enqueue_google_fonts( [ 'Poppins:400,700', 'Marcellus:400' ] );
     *
     * // Retrieve URL without enqueueing:
     * $url = buildbyhs_enqueue_google_fonts( 'Poppins:400,700', 'my-fonts', true );
     */
    function buildbyhs_enqueue_google_fonts( $fonts, $handle = 'buildbyhs-google-fonts', $return = false ) {
        // 1) Normalize input to an array of strings
        if ( ! is_array( $fonts ) ) {
            $fonts = array( $fonts );
        }

        // 2) Sanitize and prepare each family for the query
        $families = array();
        foreach ( $fonts as $font ) {
            // Strip tags and extra whitespace
            $font = sanitize_text_field( $font );
            // Replace spaces with '+' for URL encoding
            $families[] = str_replace( ' ', '+', trim( $font ) );
        }

        // 3) Build query arguments: family=Family1|Family2&display=swap
        $query_args = array(
            'family'  => implode( '|', $families ),
            'display' => 'swap',  // Use font-display: swap for performance
        );

        // 4) Generate full Google Fonts URL
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );

        // 5) If caller wants the URL only, return it (escaped)
        if ( $return ) {
            return esc_url_raw( $fonts_url );
        }

        // 6) Otherwise, enqueue the stylesheet in WP head
        wp_enqueue_style(
            $handle,
            esc_url_raw( $fonts_url ),
            array(),  // No dependencies
            null      // Let browsers cache indefinitely (URL changes when families change)
        );
    }
}
