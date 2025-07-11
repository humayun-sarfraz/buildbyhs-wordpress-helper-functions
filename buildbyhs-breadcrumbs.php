<?php
/**
 * File: buildbyhs-breadcrumbs.php
 * Function: buildbyhs_display_breadcrumbs
 * Description: Generates a breadcrumb trail for the current page or post,
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // In your theme templates (e.g., header.php or single.php):
 * if ( function_exists( 'buildbyhs_display_breadcrumbs' ) ) {
 *     buildbyhs_display_breadcrumbs();
 * }
 */

if ( ! function_exists( 'buildbyhs_display_breadcrumbs' ) ) {
    function buildbyhs_display_breadcrumbs() {
        // Settings
        $separator = ' &raquo; ';
        $home_text = __( 'Home', 'buildbyhs' );

        // Start breadcrumb
        echo '<nav class="buildbyhs-breadcrumbs" aria-label="breadcrumb"><ol>'; 

        // Home link
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( $home_text ) . '</a></li>';

        if ( is_singular() ) {
            global $post;
            $post_type = get_post_type( $post );

            // Custom post type archive link
            if ( $post_type && $post_type !== 'post' && $post_type !== 'page' ) {
                $archive_link = get_post_type_archive_link( $post_type );
                if ( $archive_link ) {
                    echo '<li>' . esc_html( $separator ) . '<a href="' . esc_url( $archive_link ) . '">' . esc_html( get_post_type_object( $post_type )->labels->name ) . '</a></li>';
                }
            }

            // Categories for posts
            if ( is_singular( 'post' ) ) {
                $categories = get_the_category( $post->ID );
                if ( ! empty( $categories ) ) {
                    $category = $categories[0];
                    $cat_link = get_category_link( $category->term_id );
                    echo '<li>' . esc_html( $separator ) . '<a href="' . esc_url( $cat_link ) . '">' . esc_html( $category->name ) . '</a></li>';
                }
            }

            // Current item
            $title = get_the_title( $post );
            echo '<li>' . esc_html( $separator ) . '<span>' . esc_html( $title ) . '</span></li>';

        } elseif ( is_archive() ) {
            // Archive pages (categories, tags, custom taxonomies, date, author)
            if ( is_category() || is_tag() || is_tax() ) {
                $term = get_queried_object();
                $name = $term->name;
                echo '<li>' . esc_html( $separator ) . '<span>' . esc_html( $name ) . '</span></li>';
            } elseif ( is_post_type_archive() ) {
                $obj = get_queried_object();
                echo '<li>' . esc_html( $separator ) . '<span>' . esc_html( $obj->labels->name ) . '</span></li>';
            } elseif ( is_author() ) {
                $author = get_queried_object();
                echo '<li>' . esc_html( $separator ) . '<span>' . esc_html( $author->display_name ) . '</span></li>';
            } elseif ( is_date() ) {
                if ( is_day() ) {
                    echo '<li>' . esc_html( $separator ) . '<span>' . get_the_date() . '</span></li>';
                } elseif ( is_month() ) {
                    echo '<li>' . esc_html( $separator ) . '<span>' . get_the_date( 'F Y' ) . '</span></li>';
                } elseif ( is_year() ) {
                    echo '<li>' . esc_html( $separator ) . '<span>' . get_the_date( 'Y' ) . '</span></li>';
                }
            }

        } elseif ( is_search() ) {
            $query = get_search_query();
            echo '<li>' . esc_html( $separator ) . '<span>' . sprintf( esc_html__( 'Search Results for "%s"', 'buildbyhs' ), esc_html( $query ) ) . '</span></li>';

        } elseif ( is_404() ) {
            echo '<li>' . esc_html( $separator ) . '<span>' . esc_html__( 'Page Not Found', 'buildbyhs' ) . '</span></li>';
        }

        echo '</ol></nav>';
    }
}
