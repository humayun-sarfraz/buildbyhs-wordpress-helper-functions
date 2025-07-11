<?php
/**
 * File: buildbyhs-woocommerce-custom-price.php
 * Function: buildbyhs_adjust_dynamic_price
 * Description: Applies dynamic price adjustments (e.g., bulk discounts or user-role discounts)
 *              before displaying product prices in WooCommerce.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-custom-price.php';
 * add_filter( 'woocommerce_product_get_price',      'buildbyhs_adjust_dynamic_price', 20, 2 );
 * add_filter( 'woocommerce_product_get_regular_price','buildbyhs_adjust_dynamic_price', 20, 2 );
 * add_filter( 'woocommerce_product_variation_get_price',      'buildbyhs_adjust_dynamic_price', 20, 2 );
 * add_filter( 'woocommerce_product_variation_get_regular_price','buildbyhs_adjust_dynamic_price', 20, 2 );
 */

if ( ! function_exists( 'buildbyhs_adjust_dynamic_price' ) ) {
    /**
     * Adjust the product price dynamically.
     *
     * @param string|float $price   Original price.
     * @param WC_Product   $product Product object.
     * @return float Adjusted price.
     */
    function buildbyhs_adjust_dynamic_price( $price, $product ) {
        // Ensure valid price and product
        $price   = floatval( $price );
        $user    = wp_get_current_user();
        $role    = ! empty( $user->roles ) ? $user->roles[0] : '';

        // 1) User-role discount (default none)
        $role_discounts = apply_filters( 'buildbyhs_role_price_discounts', array(
            // 'wholesale' => 0.80, // 20% off for wholesale
        ) );
        if ( isset( $role_discounts[ $role ] ) ) {
            $multiplier = floatval( $role_discounts[ $role ] );
            $price *= $multiplier;
        }

        // 2) Bulk discount by quantity in cart (default none)
        if ( is_cart() || is_checkout() ) {
            $bulk_thresholds = apply_filters( 'buildbyhs_bulk_price_discounts', array(
                // 10 => 0.90, // 10% off when buying 10 or more
                // 20 => 0.85, // 15% off when buying 20 or more
            ) );

            $cart = WC()->cart;
            if ( $cart ) {
                $qty = 0;
                foreach ( $cart->get_cart() as $item ) {
                    if ( $item['product_id'] == $product->get_id() ) {
                        $qty += intval( $item['quantity'] );
                    }
                }
                krsort( $bulk_thresholds );
                foreach ( $bulk_thresholds as $threshold => $mult ) {
                    if ( $qty >= intval( $threshold ) ) {
                        $price *= floatval( $mult );
                        break;
                    }
                }
            }
        }

        // 3) Ensure price is not negative
        $price = max( 0, $price );

        return $price;
    }
}
