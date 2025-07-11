<?php
/**
 * File: buildbyhs-woocommerce-auto-apply-coupon.php
 * Functions: buildbyhs_auto_apply_coupon, buildbyhs_remove_auto_coupon
 * Description: Automatically applies or removes a coupon code based on cart total or products in cart,
 *              using filterable coupon code and conditions.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-auto-apply-coupon.php';
 * add_action( 'woocommerce_before_cart', 'buildbyhs_auto_apply_coupon' );
 * add_action( 'woocommerce_before_cart', 'buildbyhs_remove_auto_coupon', 20 );
 */

if ( ! function_exists( 'buildbyhs_auto_apply_coupon' ) ) {
    /**
     * Apply coupon automatically when cart meets threshold.
     */
    function buildbyhs_auto_apply_coupon() {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            return;
        }
        $coupon_code = apply_filters( 'buildbyhs_auto_coupon_code', '' );
        $threshold   = apply_filters( 'buildbyhs_auto_coupon_threshold', 100 );

        $coupon_code = sanitize_text_field( $coupon_code );
        $threshold   = floatval( $threshold );

        if ( empty( $coupon_code ) || ! WC()->cart ) {
            return;
        }

        $subtotal = floatval( WC()->cart->get_subtotal() );

        // Apply coupon if not applied and subtotal >= threshold
        if ( $subtotal >= $threshold && ! WC()->cart->has_discount( $coupon_code ) ) {
            WC()->cart->apply_coupon( $coupon_code );
            wc_print_notice( sprintf( esc_html__( 'Coupon "%s" applied!', 'buildbyhs' ), esc_html( $coupon_code ) ), 'success' );
        }
    }
}

if ( ! function_exists( 'buildbyhs_remove_auto_coupon' ) ) {
    /**
     * Remove coupon automatically when cart falls below threshold.
     */
    function buildbyhs_remove_auto_coupon() {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            return;
        }
        $coupon_code = apply_filters( 'buildbyhs_auto_coupon_code', '' );
        $threshold   = apply_filters( 'buildbyhs_auto_coupon_threshold', 100 );

        $coupon_code = sanitize_text_field( $coupon_code );
        $threshold   = floatval( $threshold );

        if ( empty( $coupon_code ) || ! WC()->cart ) {
            return;
        }

        $subtotal = floatval( WC()->cart->get_subtotal() );

        // Remove coupon if applied and subtotal < threshold
        if ( $subtotal < $threshold && WC()->cart->has_discount( $coupon_code ) ) {
            WC()->cart->remove_coupon( $coupon_code );
            wc_print_notice( sprintf( esc_html__( 'Coupon "%s" removed (cart total below %s).', 'buildbyhs' ), esc_html( $coupon_code ), wc_price( $threshold ) ), 'notice' );
        }
    }
}
