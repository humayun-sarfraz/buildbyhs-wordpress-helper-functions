<?php
/**
 * File: buildbyhs-woocommerce-order-delivery-date.php
 * Functions: buildbyhs_add_delivery_date_field, buildbyhs_validate_delivery_date, buildbyhs_save_delivery_date, buildbyhs_display_delivery_date
 * Description: Adds a "Delivery Date" picker to checkout, validates, saves to order meta,
 *              and displays it in the admin order details and customer emails.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-order-delivery-date.php';
 * add_action( 'woocommerce_after_order_notes',          'buildbyhs_add_delivery_date_field' );
 * add_action( 'woocommerce_checkout_process',           'buildbyhs_validate_delivery_date' );
 * add_action( 'woocommerce_checkout_update_order_meta', 'buildbyhs_save_delivery_date' );
 * add_action( 'woocommerce_admin_order_data_after_billing_address', 'buildbyhs_display_delivery_date', 10, 1 );
 * add_filter( 'woocommerce_email_order_meta_fields', 'buildbyhs_email_delivery_date', 10, 3 );
 */

if ( ! function_exists( 'buildbyhs_add_delivery_date_field' ) ) {
    /**
     * Add a delivery date field to the checkout page.
     *
     * @param WC_Checkout $checkout
     */
    function buildbyhs_add_delivery_date_field( $checkout ) {
        echo '<div id="buildbyhs_delivery_date_field"><h2>' . esc_html__( 'Delivery Date', 'buildbyhs' ) . '</h2>';
        woocommerce_form_field( 'buildbyhs_delivery_date', array(
            'type'        => 'date',
            'class'       => array( 'form-row-wide' ),
            'label'       => __( 'Choose your preferred delivery date', 'buildbyhs' ),
            'required'    => true,
            'min'         => date('Y-m-d'),
        ), $checkout->get_value( 'buildbyhs_delivery_date' ) );
        echo '</div>';
    }
}

if ( ! function_exists( 'buildbyhs_validate_delivery_date' ) ) {
    /**
     * Validate the delivery date on checkout.
     */
    function buildbyhs_validate_delivery_date() {
        if ( empty( $_POST['buildbyhs_delivery_date'] ) ) {
            wc_add_notice( __( 'Please select a delivery date.', 'buildbyhs' ), 'error' );
        } else {
            $date = sanitize_text_field( wp_unslash( $_POST['buildbyhs_delivery_date'] ) );
            $today = date('Y-m-d');
            if ( $date < $today ) {
                wc_add_notice( __( 'Delivery date cannot be in the past.', 'buildbyhs' ), 'error' );
            }
        }
    }
}

if ( ! function_exists( 'buildbyhs_save_delivery_date' ) ) {
    /**
     * Save the delivery date to order meta.
     *
     * @param int $order_id
     */
    function buildbyhs_save_delivery_date( $order_id ) {
        if ( isset( $_POST['buildbyhs_delivery_date'] ) ) {
            $date = sanitize_text_field( wp_unslash( $_POST['buildbyhs_delivery_date'] ) );
            update_post_meta( $order_id, '_buildbyhs_delivery_date', $date );
        }
    }
}

if ( ! function_exists( 'buildbyhs_display_delivery_date' ) ) {
    /**
     * Display delivery date in admin order meta box.
     *
     * @param WC_Order|int $order
     */
    function buildbyhs_display_delivery_date( $order ) {
        if ( is_numeric( $order ) ) $order = wc_get_order( $order );
        $date = get_post_meta( $order->get_id(), '_buildbyhs_delivery_date', true );
        if ( $date ) {
            echo '<p><strong>' . esc_html__( 'Delivery Date:', 'buildbyhs' ) . '</strong> ' . esc_html( date_i18n( get_option('date_format'), strtotime( $date ) ) ) . '</p>';
        }
    }
}

if ( ! function_exists( 'buildbyhs_email_delivery_date' ) ) {
    /**
     * Add delivery date to customer emails.
     *
     * @param array      $fields
     * @param bool       $sent_to_admin
     * @param WC_Order   $order
     * @return array Modified fields
     */
    function buildbyhs_email_delivery_date( $fields, $sent_to_admin, $order ) {
        $date = get_post_meta( $order->get_id(), '_buildbyhs_delivery_date', true );
        if ( $date ) {
            $fields['buildbyhs_delivery_date'] = array(
                'label' => __( 'Delivery Date', 'buildbyhs' ),
                'value' => date_i18n( get_option('date_format'), strtotime( $date ) ),
            );
        }
        return $fields;
    }
}
