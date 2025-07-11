<?php
/**
 * File: buildbyhs-woocommerce-shipping-adjustment.php
 * Function: buildbyhs_adjust_shipping_rates
 * Description: Modifies available shipping rates based on cart subtotal or weight,
 *              e.g., offering free shipping over a threshold or adding handling fees.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-shipping-adjustment.php';
 * add_filter( 'woocommerce_package_rates', 'buildbyhs_adjust_shipping_rates', 20, 2 );
 */

if ( ! function_exists( 'buildbyhs_adjust_shipping_rates' ) ) {
    /**
     * Adjust shipping rates before display.
     *
     * @param array            $rates   Array of WC_Shipping_Rate objects.
     * @param WC_Customer_Bag  $package Package details.
     * @return array Modified rates.
     */
    function buildbyhs_adjust_shipping_rates( $rates, $package ) {
        // Threshold for free shipping
        $free_threshold = apply_filters( 'buildbyhs_free_shipping_threshold', 150 );
        $free_threshold = floatval( $free_threshold );

        // Handling fee for all orders
        $handling_fee = apply_filters( 'buildbyhs_handling_fee', 5 );
        $handling_fee = floatval( $handling_fee );

        // Cart subtotal
        $subtotal = WC()->cart->get_subtotal();

        foreach ( $rates as $rate_id => $rate ) {
            // If method is flat_rate or local_pickup, adjust
            if ( in_array( $rate->method_id, array( 'flat_rate', 'local_pickup' ), true ) ) {
                // Free shipping override
                if ( $subtotal >= $free_threshold ) {
                    $rate->cost = 0;
                    $rate->label .= ' (' . esc_html__( 'Free via promotion', 'buildbyhs' ) . ')';
                }
                // Apply handling fee to all
                $rate->cost += $handling_fee;
                // Taxes: propagate handling fee to taxes if set
                if ( ! empty( $rate->taxes ) ) {
                    $tax_rates = WC_Tax::get_shipping_tax_rates();
                    $additional_taxes = WC_Tax::calc_tax( $handling_fee, $tax_rates );
                    foreach ( $additional_taxes as $tax_id => $tax_amount ) {
                        if ( isset( $rate->taxes[ $tax_id ] ) ) {
                            $rate->taxes[ $tax_id ] += $tax_amount;
                        } else {
                            $rate->taxes[ $tax_id ] = $tax_amount;
                        }
                    }
                }
                // Recalculate label cost displayed
                $rates[ $rate_id ] = $rate;
            }
        }

        return $rates;
    }
}
