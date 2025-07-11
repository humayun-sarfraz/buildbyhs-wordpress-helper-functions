<?php
/**
 * File: buildbyhs-woocommerce-custom-thankyou-redirect.php
 * Function: buildbyhs_custom_thankyou_redirect
 * Description: Redirects customers to a custom ‘Thank You’ or upsell page after checkout,
 *              based on order total, products purchased, or customer role.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-custom-thankyou-redirect.php';
 * add_filter( 'woocommerce_thankyou_redirect', 'buildbyhs_custom_thankyou_redirect', 10, 2 );
 */

if ( ! function_exists( 'buildbyhs_custom_thankyou_redirect' ) ) {
    /**
     * Determine a custom URL to redirect after checkout.
     *
     * @param string $redirect   Default redirect URL (order received page).
     * @param int    $order_id   Order ID.
     * @return string URL to redirect the customer to.
     */
    function buildbyhs_custom_thankyou_redirect( $redirect, $order_id ) {
        if ( ! $order_id ) {
            return $redirect;
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return $redirect;
        }

        // Example 1: If order total exceeds a threshold
        $threshold = apply_filters( 'buildbyhs_thankyou_redirect_min_total', 100 );
        if ( floatval( $order->get_total() ) > floatval( $threshold ) ) {
            $url = apply_filters( 'buildbyhs_thankyou_redirect_high_value_url', home_url( '/thank-you-high-value/' ) );
            return esc_url_raw( $url );
        }

        // Example 2: If customer has purchased a specific product
        $product_ids = apply_filters( 'buildbyhs_thankyou_redirect_product_ids', array() );
        if ( ! empty( $product_ids ) ) {
            foreach ( $order->get_items() as $item ) {
                if ( in_array( $item->get_product_id(), $product_ids, true ) ) {
                    $url = apply_filters( 'buildbyhs_thankyou_redirect_product_url', home_url( '/special-thank-you/' ) );
                    return esc_url_raw( $url );
                }
            }
        }

        // Example 3: Based on customer role
        $user = $order->get_user();
        if ( $user && is_user_logged_in() ) {
            $role_urls = apply_filters( 'buildbyhs_thankyou_redirect_by_role', array(
                // 'subscriber'   => home_url( '/member-thank-you/' ),
                // 'wholesale'    => home_url( '/wholesale-thank-you/' ),
            ) );
            foreach ( $user->roles as $role ) {
                if ( isset( $role_urls[ $role ] ) ) {
                    return esc_url_raw( $role_urls[ $role ] );
                }
            }
        }

        // Default: return original
        return $redirect;
    }
}
