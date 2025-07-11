<?php
/**
 * File: buildbyhs-seo-meta-tags.php
 * Function: buildbyhs_seo_meta_tags
 * Description: Outputs basic SEO meta tags (title, description, Open Graph) for the current page or post,
 *              with sanitization and escaping.
 *
 * Usage:
 * // In your theme's header.php within <head>:
 * if ( function_exists( 'buildbyhs_seo_meta_tags' ) ) {
 *     buildbyhs_seo_meta_tags();
 * }
 */

if ( ! function_exists( 'buildbyhs_seo_meta_tags' ) ) {
    function buildbyhs_seo_meta_tags() {
        if ( is_singular() ) {
            global $post;
            $title       = get_the_title( $post );
            $description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
            if ( ! $description ) {
                $excerpt = wp_strip_all_tags( $post->post_excerpt ? $post->post_excerpt : $post->post_content );
                $description = mb_substr( $excerpt, 0, 155 );
            }
            $url  = get_permalink( $post );
            $image = get_the_post_thumbnail_url( $post, 'full' );
        } else {
            $title       = get_bloginfo( 'name' );
            $description = get_bloginfo( 'description' );
            $url         = home_url();
            $image       = ''; // could default to a site-wide image
        }

        // Sanitize outputs
        $title       = esc_attr( $title );
        $description = esc_attr( $description );
        $url         = esc_url( $url );
        $image       = esc_url( $image );

        // Output meta tags
        echo '<meta name="description" content="' . $description . '" />';
        echo '<meta property="og:title" content="' . $title . '" />';
        echo '<meta property="og:description" content="' . $description . '" />';
        echo '<meta property="og:url" content="' . $url . '" />';
        if ( $image ) {
            echo '<meta property="og:image" content="' . $image . '" />';
        }
        echo '<meta name="twitter:card" content="summary_large_image" />';
        echo '<meta name="twitter:title" content="' . $title . '" />';
        echo '<meta name="twitter:description" content="' . $description . '" />';
        if ( $image ) {
            echo '<meta name="twitter:image" content="' . $image . '" />';
        }
    }
}
