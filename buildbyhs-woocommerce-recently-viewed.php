<?php
/**
 * File: buildbyhs-woocommerce-recently-viewed.php
 * Functions: buildbyhs_track_recently_viewed, buildbyhs_display_recently_viewed
 * Description: Tracks products viewed by the user in a session and displays a list of recently viewed products.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-recently-viewed.php';
 * add_action( 'template_redirect', 'buildbyhs_track_recently_viewed' );
 * // To display recently viewed products (e.g. in sidebar or template):
 * if ( function_exists( 'buildbyhs_display_recently_viewed' ) ) {
 *     buildbyhs_display_recently_viewed( array(
 *         'limit'   => 5,
 *         'title'   => __( 'Recently Viewed', 'buildbyhs' ),
 *         'columns' => 4,
 *     ) );
 * }
 */

if ( ! function_exists( 'buildbyhs_track_recently_viewed' ) ) {
    /**
     * Adds current product ID to the session or cookie for recently viewed tracking.
     */
    function buildbyhs_track_recently_viewed() {
        if ( ! is_singular( 'product' ) ) {
            return;
        }
        global $post;
        $id = intval( $post->ID );
        if ( ! $id ) {
            return;
        }
        // Initialize
        $viewed = isset( $_COOKIE['buildbyhs_viewed_products'] ) ? wc_clean( wp_unslash( $_COOKIE['buildbyhs_viewed_products'] ) ) : '';
        $viewed = $viewed ? explode( '|', $viewed ) : array();
        // Remove this ID if already present
        $viewed = array_diff( $viewed, array( (string) $id ) );
        // Prepend
        array_unshift( $viewed, (string) $id );
        // Limit stored values
        $limit = apply_filters( 'buildbyhs_recently_viewed_limit', 20 );
        $viewed = array_slice( $viewed, 0, intval( $limit ) );
        // Store back as cookie for 30 days
        wc_setcookie( 'buildbyhs_viewed_products', implode( '|', $viewed ), time() + ( DAY_IN_SECONDS * 30 ) );
    }
}

if ( ! function_exists( 'buildbyhs_display_recently_viewed' ) ) {
    /**
     * Outputs a grid of recently viewed products.
     *
     * @param array $args {
     *     Optional arguments.
     *     @type int    $limit   Number of products to show. Default 5.
     *     @type string $title   Section title. Default empty.
     *     @type int    $columns Number of columns in grid. Default 4.
     * }
     */
    function buildbyhs_display_recently_viewed( $args = array() ) {
        $defaults = array(
            'limit'   => 5,
            'title'   => '',
            'columns' => 4,
        );
        $r = wp_parse_args( $args, $defaults );

        // Get viewed IDs
        $viewed = isset( $_COOKIE['buildbyhs_viewed_products'] ) ? wc_clean( wp_unslash( $_COOKIE['buildbyhs_viewed_products'] ) ) : '';
        $viewed = $viewed ? explode( '|', $viewed ) : array();
        if ( empty( $viewed ) ) {
            return;
        }
        // Limit
        $viewed = array_slice( $viewed, 0, intval( $r['limit'] ) );

        // Query
        $query = new WP_Query( array(
            'post_type'      => 'product',
            'post__in'       => array_map( 'intval', $viewed ),
            'posts_per_page' => count( $viewed ),
            'orderby'        => 'post__in',
        ) );

        if ( $query->have_posts() ) {
            echo '<section class="buildbyhs-recently-viewed">';
            if ( $r['title'] ) {
                echo '<h2>' . esc_html( $r['title'] ) . '</h2>';
            }
            woocommerce_product_loop_start( false );
            $count = 0;
            while ( $query->have_posts() ) {
                $query->the_post();
                wc_get_template_part( 'content', 'product' );
                $count++;
            }
            woocommerce_product_loop_end( false );
            echo '</section>';
            wp_reset_postdata();
        }
    }
}
