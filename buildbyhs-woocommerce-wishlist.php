<?php
/**
 * File: buildbyhs-woocommerce-wishlist.php
 * Functions: buildbyhs_add_to_wishlist_button, buildbyhs_handle_wishlist_action, buildbyhs_display_wishlist
 * Description: Provides a simple wishlist feature using cookies, with an "Add to Wishlist" button,
 *              AJAX handler for add/remove, and display of saved products.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-wishlist.php';
 * add_action( 'woocommerce_after_shop_loop_item',    'buildbyhs_add_to_wishlist_button', 20 );
 * add_action( 'woocommerce_single_product_summary',  'buildbyhs_add_to_wishlist_button', 35 );
 * add_action( 'wp_ajax_buildbyhs_wishlist_toggle',   'buildbyhs_handle_wishlist_action' );
 * add_action( 'wp_ajax_nopriv_buildbyhs_wishlist_toggle', 'buildbyhs_handle_wishlist_action' );
 *
 * // To display wishlist (e.g., on a page template):
 * if ( function_exists( 'buildbyhs_display_wishlist' ) ) {
 *     buildbyhs_display_wishlist();
 * }
 */

if ( ! function_exists( 'buildbyhs_get_wishlist' ) ) {
    /**
     * Retrieve the wishlist array from cookie.
     *
     * @return array List of product IDs.
     */
    function buildbyhs_get_wishlist() {
        $cookie = isset( $_COOKIE['buildbyhs_wishlist'] ) ? wc_clean( wp_unslash( $_COOKIE['buildbyhs_wishlist'] ) ) : '';
        $ids    = $cookie ? explode( '|', $cookie ) : array();
        return array_map( 'intval', array_filter( $ids ) );
    }
}

if ( ! function_exists( 'buildbyhs_set_wishlist' ) ) {
    /**
     * Store the wishlist array into a cookie.
     *
     * @param array $ids List of product IDs.
     */
    function buildbyhs_set_wishlist( $ids ) {
        $ids      = array_unique( array_map( 'intval', $ids ) );
        $value    = implode( '|', $ids );
        wc_setcookie( 'buildbyhs_wishlist', $value, time() + DAY_IN_SECONDS * 30 );
    }
}

if ( ! function_exists( 'buildbyhs_add_to_wishlist_button' ) ) {
    /**
     * Output an "Add/Remove from Wishlist" button with AJAX data.
     */
    function buildbyhs_add_to_wishlist_button() {
        global $product;
        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $id      = $product->get_id();
        $wishlist = buildbyhs_get_wishlist();
        $active  = in_array( $id, $wishlist, true );
        $label   = $active ? esc_html__( 'Remove from Wishlist', 'buildbyhs' ) : esc_html__( 'Add to Wishlist', 'buildbyhs' );
        $nonce   = wp_create_nonce( 'buildbyhs_wishlist_' . $id );

        printf(
            '<button class="buildbyhs-wishlist-btn %s" data-product_id="%d" data-nonce="%s">%s</button>',
            $active ? 'in-wishlist' : 'not-in-wishlist',
            esc_attr( $id ),
            esc_attr( $nonce ),
            esc_html( $label )
        );
    }
}

if ( ! function_exists( 'buildbyhs_handle_wishlist_action' ) ) {
    /**
     * AJAX handler to toggle a product in the wishlist.
     */
    function buildbyhs_handle_wishlist_action() {
        $pid   = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        $nonce = isset( $_POST['nonce'] )      ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'buildbyhs_wishlist_' . $pid ) || ! $pid ) {
            wp_send_json_error();
        }

        $wishlist = buildbyhs_get_wishlist();
        if ( in_array( $pid, $wishlist, true ) ) {
            $wishlist = array_diff( $wishlist, array( $pid ) );
            $status   = 'removed';
        } else {
            $wishlist[] = $pid;
            $status     = 'added';
        }
        buildbyhs_set_wishlist( $wishlist );
        wp_send_json_success( array( 'status' => $status, 'count' => count( $wishlist ) ) );
    }
}

if ( ! function_exists( 'buildbyhs_display_wishlist' ) ) {
    /**
     * Display the wishlist products in a grid.
     *
     * @param array $args Optional args: 'title', 'limit', 'columns'.
     */
    function buildbyhs_display_wishlist( $args = array() ) {
        $defaults = array( 'title' => __( 'Your Wishlist', 'buildbyhs' ), 'limit' => 10, 'columns' => 4 );
        $r = wp_parse_args( $args, $defaults );

        $wishlist = buildbyhs_get_wishlist();
        if ( empty( $wishlist ) ) {
            echo '<p class="buildbyhs-wishlist-empty">' . esc_html__( 'Your wishlist is empty.', 'buildbyhs' ) . '</p>';
            return;
        }
        $ids   = array_slice( $wishlist, 0, intval( $r['limit'] ) );

        $query = new WP_Query( array(
            'post_type'      => 'product',
            'post__in'       => $ids,
            'orderby'        => 'post__in',
            'posts_per_page' => count( $ids ),
        ) );

        if ( $query->have_posts() ) {
            echo '<section class="buildbyhs-wishlist"><h2>' . esc_html( $r['title'] ) . '</h2>';
            woocommerce_product_loop_start();
            while ( $query->have_posts() ) {
                $query->the_post();
                wc_get_template_part( 'content', 'product' );
            }
            woocommerce_product_loop_end();
            echo '</section>';
            wp_reset_postdata();
        }
    }
}
