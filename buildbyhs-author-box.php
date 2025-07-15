<?php
/**
 * File: buildbyhs-author-box.php
 * Function: buildbyhs_display_author_box
 * Description: Outputs a styled author bio box at the end of single posts, including avatar, bio, and social links.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-author-box.php';
 * add_action( 'the_content', 'buildbyhs_display_author_box' );
 */

if ( ! function_exists( 'buildbyhs_display_author_box' ) ) {
    /**
     * Append an author box to single post content.
     *
     * @param string $content Post content.
     * @return string Modified content.
     */
    function buildbyhs_display_author_box( $content ) {
        if ( ! is_singular( 'post' ) || ! in_the_loop() || is_admin() ) {
            return $content;
        }

        global $post;
        $author_id = $post->post_author;
        $avatar    = get_avatar( $author_id, 96 );
        $name      = get_the_author_meta( 'display_name', $author_id );
        $description = get_the_author_meta( 'description', $author_id );

        // Optional social fields
        $twitter  = get_the_author_meta( 'twitter', $author_id );
        $facebook = get_the_author_meta( 'facebook', $author_id );
        $linkedin = get_the_author_meta( 'linkedin', $author_id );

        // Build social links HTML
        $social_links = '';
        $services = array(
            'twitter'  => $twitter,
            'facebook' => $facebook,
            'linkedin' => $linkedin,
        );
        foreach ( $services as $service => $handle ) {
            if ( $handle ) {
                $url = esc_url( $handle );
                $social_links .= sprintf(
                    '<a href="%1$s" class="author-social author-%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
                    $url,
                    esc_attr( $service ),
                    esc_html( ucfirst( $service ) )
                );
            }
        }

        // Assemble box
        $html  = '<div class="buildbyhs-author-box">';
        $html .= '<div class="author-avatar">' . $avatar . '</div>';
        $html .= '<div class="author-info">';
        $html .= '<h3 class="author-name">' . esc_html( $name ) . '</h3>';
        if ( $description ) {
            $html .= '<p class="author-bio">' . esc_html( $description ) . '</p>';
        }
        if ( $social_links ) {
            $html .= '<div class="author-social-links">' . $social_links . '</div>';
        }
        $html .= '</div></div>';

        return $content . $html;
    }
}
