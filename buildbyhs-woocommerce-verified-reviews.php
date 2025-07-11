<?php
/**
 * File: buildbyhs-woocommerce-verified-reviews.php
 * Functions: buildbyhs_disable_reviews_for_non_purchased, buildbyhs_hide_review_tab
 * Description: Restricts product reviews to verified purchasers only by removing the review form
 *              and hiding the Reviews tab for users who haven’t bought the product.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-verified-reviews.php';
 * add_filter( 'comment_form_defaults',    'buildbyhs_disable_reviews_for_non_purchased' );
 * add_filter( 'woocommerce_product_tabs', 'buildbyhs_hide_review_tab', 99 );
 */

if ( ! function_exists( 'buildbyhs_disable_reviews_for_non_purchased' ) ) {
    /**
     * Disable the comment (review) form if the user hasn't purchased the product.
     *
     * @param array $defaults Comment form defaults.
     * @return array Modified defaults (empty form) or original.
     */
    function buildbyhs_disable_reviews_for_non_purchased( $defaults ) {
        if ( ! is_singular( 'product' ) || ! comments_open() ) {
            return $defaults;
        }

        global $product;
        $user_id = get_current_user_id();

        // If user not logged in, disable reviews
        if ( ! $user_id ) {
            $defaults['comment_field'] = '<p class="verified-only">' . esc_html__( 'Only verified purchasers can leave a review.', 'buildbyhs' ) . '</p>';
            return $defaults;
        }

        // Check if user has purchased this product
        if ( ! wc_customer_bought_product( wp_get_current_user()->user_email, $user_id, $product->get_id() ) ) {
            $defaults['comment_field'] = '<p class="verified-only">' . esc_html__( 'Only verified purchasers can leave a review.', 'buildbyhs' ) . '</p>';
        }

        return $defaults;
    }
}

if ( ! function_exists( 'buildbyhs_hide_review_tab' ) ) {
    /**
     * Hide the Reviews tab entirely if the user cannot review.
     *
     * @param array $tabs Existing product tabs.
     * @return array Modified tabs.
     */
    function buildbyhs_hide_review_tab( $tabs ) {
        if ( ! is_singular( 'product' ) ) {
            return $tabs;
        }

        global $product;
        $user_id = get_current_user_id();
        $can_review = false;

        if ( $user_id && wc_customer_bought_product( wp_get_current_user()->user_email, $user_id, $product->get_id() ) ) {
            $can_review = true;
        }

        if ( ! $can_review ) {
            unset( $tabs['reviews'] );
        }
        return $tabs;
    }
}
