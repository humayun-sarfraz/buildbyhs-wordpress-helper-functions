<?php
/**
 * File: buildbyhs-woocommerce-cart-note.php
 * Functions: buildbyhs_add_custom_cart_fee, buildbyhs_add_cart_notice
 * Description: Adds a custom fee or notice to the cart based on cart total or item conditions,
 *              with filterable thresholds and proper sanitization.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-cart-note.php';
 * add_action( 'woocommerce_cart_calculate_fees', 'buildbyhs_add_custom_cart_fee', 20 );
 * add_action( 'woocommerce_before_cart',       'buildbyhs_add_cart_notice',    10 );
 */

if ( ! function_exists( 'buildbyhs_add_custom_cart_fee' ) ) {
    /**
     * Add a custom fee when cart total is below a threshold.
     *
     * @param WC_Cart $cart Cart object.
     */
    function buildbyhs_add_custom_cart_fee( $cart ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            return;
        }

        // Get threshold and fee amount (filters allow customization)
        $threshold = apply_filters( 'buildbyhs_cart_fee_threshold', 50 );  // e.g. $50
        $fee       = apply_filters( 'buildbyhs_cart_fee_amount',    5 );  // e.g. $5

        // Sanitize
        $threshold = floatval( $threshold );
        $fee       = floatval( $fee );

        // Cart subtotal (excl. taxes)
        $subtotal = floatval( $cart->get_subtotal() );

        if ( $subtotal > 0 && $subtotal < $threshold ) {
            $label = apply_filters( 'buildbyhs_cart_fee_label', __( 'Small order fee', 'buildbyhs' ) );
            $label = sanitize_text_field( $label );
            $cart->add_fee( $label, $fee, true );
        }
    }
}

if ( ! function_exists( 'buildbyhs_add_cart_notice' ) ) {
    /**
     * Display a notice on the cart page for orders below threshold.
     */
    function buildbyhs_add_cart_notice() {
        if ( is_admin() ) {
            return;
        }

        $threshold = apply_filters( 'buildbyhs_cart_fee_threshold', 50 );
        $subtotal  = WC()->cart ? floatval( WC()->cart->get_subtotal() ) : 0;

        if ( $subtotal > 0 && $subtotal < $threshold ) {
            $message = sprintf(
                /* translators: %1$s threshold, %2$s fee */
                __( 'Add %1$s more to your cart to avoid a %2$s small order fee.', 'buildbyhs' ),
                wc_price( $threshold - $subtotal ),
                wc_price( apply_filters( 'buildbyhs_cart_fee_amount', 5 ) )
            );
            wc_print_notice( wp_kses_post( $message ), 'notice' );
        }
    }
}
