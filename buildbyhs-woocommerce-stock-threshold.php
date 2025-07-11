<?php
/**
 * File: buildbyhs-woocommerce-stock-threshold.php
 * Function: buildbyhs_low_stock_notice
 * Description: Displays a low-stock notice on single product pages when stock is below threshold,
 *              with customizable threshold and proper sanitization and escaping.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-stock-threshold.php';
 * add_action( 'woocommerce_single_product_summary', 'buildbyhs_low_stock_notice', 15 );
 */

if ( ! function_exists( 'buildbyhs_low_stock_notice' ) ) {
    /**
     * Show a notice if product stock is below threshold.
     */
    function buildbyhs_low_stock_notice() {
        global $product;

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        // Only for in-stock, managing stock products
        if ( ! $product->managing_stock() || ! $product->is_in_stock() ) {
            return;
        }

        // Get threshold via filter (default 5)
        $threshold = apply_filters( 'buildbyhs_stock_threshold', 5 );
        $threshold = intval( $threshold );

        $stock = intval( $product->get_stock_quantity() );

        if ( $stock > 0 && $stock <= $threshold ) {
            /* translators: %s: number of items left */
            $message = sprintf( esc_html__( 'Hurry! Only %s left in stock.', 'buildbyhs' ), esc_html( $stock ) );
            echo '<p class="buildbyhs-stock-notice">' . $message . '</p>';
        }
    }
}
