<?php
/**
 * File: buildbyhs-related-posts.php
 * Function: buildbyhs_related_posts_by_taxonomy
 * Description: Displays a list of related posts based on shared taxonomy terms (categories or tags),
 *              with proper sanitization, escaping, and optional shortcode usage.
 *
 * Usage:
 * // Include in your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-related-posts.php';
 *
 * // 1) Call directly in template:
 * buildbyhs_related_posts_by_taxonomy( array(
 *     'post_id'    => get_the_ID(),
 *     'taxonomy'   => 'category',
 *     'number'     => 5,
 *     'title'      => __( 'Related Posts', 'buildbyhs' ),
 * ) );
 *
 * // 2) Use via shortcode: [related_posts taxonomy="post_tag" number="3"]
 */

if ( ! function_exists( 'buildbyhs_related_posts_by_taxonomy' ) ) {
    /**
     * Outputs related posts for a given post ID and taxonomy.
     *
     * @param array $args {
     *     Optional. Array of arguments.
     *     @type int    $post_id  Post ID to find related posts for. Default current post.
     *     @type string $taxonomy Taxonomy to use ('category' or 'post_tag'). Default 'category'.
     *     @type int    $number   Number of related posts to display. Default 4.
     *     @type string $title    Heading text. Default empty (no heading).
     * }
     */
    function buildbyhs_related_posts_by_taxonomy( $args = array() ) {
        $defaults = array(
            'post_id'  => get_the_ID(),
            'taxonomy' => 'category',
            'number'   => 4,
            'title'    => '',
        );
        $r = wp_parse_args( $args, $defaults );

        $post_id  = intval( $r['post_id'] );
        $taxonomy = sanitize_key( $r['taxonomy'] );
        $number   = intval( $r['number'] );
        $title    = sanitize_text_field( $r['title'] );

        if ( ! in_array( $taxonomy, get_object_taxonomies( 'post' ), true ) ) {
            return;
        }

        // Get terms for this post
        $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }

        // Query related posts
        $query = new WP_Query( array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'post__not_in'   => array( $post_id ),
            'posts_per_page' => $number,
            'tax_query'      => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $terms,
                ),
            ),
        ) );

        if ( $query->have_posts() ) {
            echo '<div class="buildbyhs-related-posts">';
            if ( $title ) {
                echo '<h3>' . esc_html( $title ) . '</h3>';
            }
            echo '<ul>';  
            while ( $query->have_posts() ) {
                $query->the_post();
                printf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    esc_url( get_permalink() ),
                    esc_html( get_the_title() )
                );
            }
            echo '</ul></div>';
            wp_reset_postdata();
        }
    }
}

// Shortcode fallback
if ( ! function_exists( 'buildbyhs_related_posts_shortcode' ) ) {
    function buildbyhs_related_posts_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'taxonomy' => 'category',
            'number'   => 4,
            'title'    => '',
        ), $atts, 'related_posts' );

        ob_start();
        buildbyhs_related_posts_by_taxonomy( array(
            'post_id'  => get_the_ID(),
            'taxonomy' => $atts['taxonomy'],
            'number'   => $atts['number'],
            'title'    => $atts['title'],
        ) );
        return ob_get_clean();
    }
    add_shortcode( 'related_posts', 'buildbyhs_related_posts_shortcode' );
}
