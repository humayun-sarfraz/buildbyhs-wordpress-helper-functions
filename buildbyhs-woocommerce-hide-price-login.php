<?php
/**
 * File: buildbyhs-woocommerce-hide-price-login.php
 * Functions: buildbyhs_hide_price_for_guests, buildbyhs_remove_add_to_cart_for_guests
 * Description: Hides product prices and "Add to Cart" buttons for non-logged-in users,
 *              prompting them to log in to view prices or purchase.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-hide-price-login.php';
 * add_filter( 'woocommerce_get_price_html',           'buildbyhs_hide_price_for_guests',        10, 2 );
 * add_action( 'woocommerce_single_product_summary',   'buildbyhs_remove_add_to_cart_for_guests', 1 );
 * add_action( 'woocommerce_loop_add_to_cart_link',    'buildbyhs_remove_add_to_cart_for_guests', 10 );
 */

if ( ! function_exists( 'buildbyhs_hide_price_for_guests' ) ) {
    /**
     * Replace price HTML with a login prompt for guests.
     *
     * @param string     $price_html The original price HTML.
     * @param WC_Product $product    The product object.
     * @return string Modified price HTML.
     */
    function buildbyhs_hide_price_for_guests( $price_html, $product ) {
        if ( is_user_logged_in() ) {
            return $price_html;
        }

        // Translation-ready prompt
        $prompt = apply_filters( 'buildbyhs_price_login_prompt', __( 'Please <a href="%s">log in</a> to view price', 'buildbyhs' ) );
        $login_url = esc_url( wp_login_url( get_permalink( $product->get_id() ) ) );
        return '<span class="buildbyhs-login-prompt">' . sprintf( wp_kses_post( $prompt ), $login_url ) . '</span>';
    }
}

if ( ! function_exists( 'buildbyhs_remove_add_to_cart_for_guests' ) ) {
    /**
     * Remove the add to cart button and replace with login prompt for guests.
     */
    function buildbyhs_remove_add_to_cart_for_guests() {
        if ( ! is_user_logged_in() ) {
            // Remove default add to cart actions
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            remove_action( 'woocommerce_after_shop_loop_item',    'woocommerce_template_loop_add_to_cart',   10 );

            // Output login button
            $login_url = esc_url( wp_login_url( get_permalink() ) );
            echo '<a href="' . $login_url . '" class="button buildbyhs-login-button">' . esc_html__( 'Log in to purchase', 'buildbyhs' ) . '</a>';
        }
    }
}
