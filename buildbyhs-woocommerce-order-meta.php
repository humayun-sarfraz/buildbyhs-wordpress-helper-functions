<?php
/**
 * File: buildbyhs-woocommerce-order-meta.php
 * Functions: buildbyhs_add_order_meta_field, buildbyhs_save_order_meta_field, buildbyhs_display_order_meta
 * Description: Adds a custom "Gift Message" field on checkout, saves it to order meta,
 *              and displays it on the thank you page and admin order details.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-order-meta.php';
 * add_action( 'woocommerce_after_order_notes',        'buildbyhs_add_order_meta_field' );
 * add_action( 'woocommerce_checkout_update_order_meta','buildbyhs_save_order_meta_field' );
 * add_action( 'woocommerce_thankyou',                 'buildbyhs_display_order_meta', 20 );
 * add_action( 'woocommerce_admin_order_data_after_billing_address', 'buildbyhs_display_order_meta', 10, 1 );
 */

if ( ! function_exists( 'buildbyhs_add_order_meta_field' ) ) {
    /**
     * Output a custom gift message field on checkout.
     *
     * @param WC_Checkout $checkout Checkout object.
     */
    function buildbyhs_add_order_meta_field( $checkout ) {
        echo '<div id="buildbyhs_gift_message_field"><h2>' . esc_html__( 'Gift Message', 'buildbyhs' ) . '</h2>';
        woocommerce_form_field( 'buildbyhs_gift_message', array(
            'type'        => 'textarea',
            'class'       => array( 'form-row-wide' ),
            'label'       => __( 'Enter a gift message (optional):', 'buildbyhs' ),
            'placeholder' => __( 'Your message to the recipient', 'buildbyhs' ),
            'required'    => false,
        ), $checkout->get_value( 'buildbyhs_gift_message' ) );
        echo '</div>';
    }
}

if ( ! function_exists( 'buildbyhs_save_order_meta_field' ) ) {
    /**
     * Save the gift message to order meta.
     *
     * @param int $order_id Order ID.
     */
    function buildbyhs_save_order_meta_field( $order_id ) {
        if ( isset( $_POST['buildbyhs_gift_message'] ) ) {
            $gift_message = sanitize_textarea_field( wp_unslash( $_POST['buildbyhs_gift_message'] ) );
            if ( $gift_message ) {
                update_post_meta( $order_id, '_buildbyhs_gift_message', $gift_message );
            }
        }
    }
}

if ( ! function_exists( 'buildbyhs_display_order_meta' ) ) {
    /**
     * Display the gift message on thank you page or admin order details.
     *
     * @param WC_Order|int $order Order object or ID.
     */
    function buildbyhs_display_order_meta( $order ) {
        if ( is_numeric( $order ) ) {
            $order = wc_get_order( $order );
        }
        if ( ! $order instanceof WC_Order ) {
            return;
        }
        $gift_message = get_post_meta( $order->get_id(), '_buildbyhs_gift_message', true );
        if ( $gift_message ) {
            echo '<p><strong>' . esc_html__( 'Gift Message:', 'buildbyhs' ) . '</strong><br />' . esc_html( $gift_message ) . '</p>';
        }
    }
}
