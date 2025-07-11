<?php
/**
 * File: buildbyhs-social-share-buttons.php
 * Function: buildbyhs_social_share_buttons
 * Description: Generates a set of social share links for current post, sanitized and escaped.
 *
 * Usage:
 * // In your single.php template or via shortcode:
 * echo buildbyhs_social_share_buttons( array( 'twitter', 'facebook', 'linkedin' ) );
 */

if ( ! function_exists( 'buildbyhs_social_share_buttons' ) ) {
    /**
     * Output social share buttons for the current post.
     *
     * @param array $networks List of networks to include: twitter, facebook, linkedin, pinterest.
     * @return string HTML markup of share links.
     */
    function buildbyhs_social_share_buttons( $networks = array() ) {
        if ( ! is_singular() || empty( $networks ) || ! is_array( $networks ) ) {
            return '';
        }

        global $post;
        $url   = rawurlencode( get_permalink( $post->ID ) );
        $title = rawurlencode( get_the_title( $post->ID ) );

        $services = array(
            'twitter'  => 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url,
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
            'pinterest'=> 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title,
        );

        $output = '<div class="buildbyhs-social-share">';
        foreach ( $networks as $network ) {
            if ( isset( $services[ $network ] ) ) {
                $share_url = esc_url( $services[ $network ] );
                $label     = esc_html( ucfirst( $network ) );
                $output   .= sprintf(
                    '<a class="share-btn share-%1$s" href="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
                    esc_attr( $network ),
                    $share_url,
                    $label
                );
            }
        }
        $output .= '</div>';

        return $output;
    }
}
