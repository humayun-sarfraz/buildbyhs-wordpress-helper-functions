<?php
/**
 * File: buildbyhs-woocommerce-hide-shipping-virtual.php
 * Function: buildbyhs_hide_shipping_for_virtual
 * Description: Hides all shipping methods when the cart contains only virtual or downloadable products.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-hide-shipping-virtual.php';
 * add_filter( 'woocommerce_package_rates', 'buildbyhs_hide_shipping_for_virtual', 20, 2 );
 */

if ( ! function_exists( 'buildbyhs_hide_shipping_for_virtual' ) ) {
    /**
     * Remove shipping rates if all items in the cart are virtual or downloadable.
     *
     * @param array           $rates   Array of WC_Shipping_Rate objects.
     * @param WC_Customer_Bag $package Package details.
     * @return array Modified rates.
     */
    function buildbyhs_hide_shipping_for_virtual( $rates, $package ) {
        if ( ! WC()->cart ) {
            return $rates;
        }

        // Check cart items
        $only_virtual = true;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product = wc_get_product( $cart_item['product_id'] );
            if ( $product instanceof WC_Product && ! ( $product->is_virtual() && ! $product->is_downloadable() ) ) {
                $only_virtual = false;
                break;
            }
        }

        // If only virtual products, remove all shipping methods
        if ( $only_virtual ) {
            return array();
        }

        return $rates;
    }
}
