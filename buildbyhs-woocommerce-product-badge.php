<?php
/**
 * File: buildbyhs-woocommerce-product-badge.php
 * Function: buildbyhs_woocommerce_product_badge
 * Description: Displays a "Sale" or "New" badge on product thumbnails based on sale status or publish date.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-product-badge.php';
 * add_action( 'woocommerce_before_shop_loop_item_title', 'buildbyhs_woocommerce_product_badge', 10 );
 */

if ( ! function_exists( 'buildbyhs_woocommerce_product_badge' ) ) {
    /**
     * Outputs a badge on the product thumbnail.
     */
    function buildbyhs_woocommerce_product_badge() {
        global $product;

        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            return;
        }

        // Sale badge
        if ( $product->is_on_sale() ) {
            echo '<span class="buildbyhs-badge sale-badge">' . esc_html__( 'Sale', 'buildbyhs' ) . '</span>';
            return;
        }

        // New badge: published within x days
        $threshold_days = apply_filters( 'buildbyhs_new_badge_days', 14 );
        $post_date      = get_the_date( 'U', $product->get_id() );
        $age_days       = ( time() - intval( $post_date ) ) / DAY_IN_SECONDS;

        if ( $age_days <= intval( $threshold_days ) ) {
            echo '<span class="buildbyhs-badge new-badge">' . esc_html__( 'New', 'buildbyhs' ) . '</span>';
        }
    }
}
