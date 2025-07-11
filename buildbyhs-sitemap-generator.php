<?php
/**
 * File: buildbyhs-sitemap-generator.php
 * Function: buildbyhs_generate_sitemap_xml
 * Description: Generates a simple XML sitemap of public posts and pages, with proper sanitization and escaping.
 *
 * Usage:
 * // To generate and echo sitemap in a custom endpoint:
 * add_action( 'template_redirect', function() {
 *     if ( isset( $_GET['sitemap'] ) && $_GET['sitemap'] === 'xml' ) {
 *         buildbyhs_generate_sitemap_xml();
 *         exit;
 *     }
 * } );
 */

if ( ! function_exists( 'buildbyhs_generate_sitemap_xml' ) ) {
    function buildbyhs_generate_sitemap_xml() {
        // Set headers
        header( 'Content-Type: application/xml; charset=utf-8' );

        // Start XML
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Query public posts and pages
        $args = array(
            'post_type'      => array( 'post', 'page' ),
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $permalink = esc_url( get_permalink() );
                $modified  = esc_html( get_the_modified_date( 'c' ) );
                echo '<url>';
                echo '<loc>' . $permalink . '</loc>';
                echo '<lastmod>' . $modified . '</lastmod>';
                echo '</url>';
            }
            wp_reset_postdata();
        }

        echo '</urlset>';
    }
}
