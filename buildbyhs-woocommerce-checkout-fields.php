<?php
/**
 * File: buildbyhs-woocommerce-checkout-fields.php
 * Functions: buildbyhs_modify_checkout_fields, buildbyhs_validate_vat_field, buildbyhs_save_vat_field
 * Description: Customize WooCommerce checkout fields: reorder, add a VAT number field, validate and save it.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-checkout-fields.php';
 * add_filter( 'woocommerce_checkout_fields',      'buildbyhs_modify_checkout_fields', 20 );
 * add_action( 'woocommerce_checkout_process',     'buildbyhs_validate_vat_field' );
 * add_action( 'woocommerce_checkout_update_order_meta', 'buildbyhs_save_vat_field' );
 */

if ( ! function_exists( 'buildbyhs_modify_checkout_fields' ) ) {
    /**
     * Modify checkout fields: reorder phone, add VAT number.
     *
     * @param array $fields Existing checkout fields.
     * @return array Modified fields.
     */
    function buildbyhs_modify_checkout_fields( $fields ) {
        // Reorder billing phone to appear after email
        if ( isset( $fields['billing']['billing_phone'] ) ) {
            $phone = $fields['billing']['billing_phone'];
            unset( $fields['billing']['billing_phone'] );
            // Insert after billing_email
            $new = array();
            foreach ( $fields['billing'] as $key => $field ) {
                $new[ $key ] = $field;
                if ( 'billing_email' === $key ) {
                    $new['billing_phone'] = $phone;
                }
            }
            $fields['billing'] = $new;
        }

        // Add VAT number field
        $fields['billing']['billing_vat_number'] = array(
            'type'        => 'text',
            'label'       => __( 'VAT Number', 'buildbyhs' ),
            'required'    => false,
            'class'       => array( 'form-row-wide' ),
            'priority'    => 105,
        );

        return $fields;
    }
}

if ( ! function_exists( 'buildbyhs_validate_vat_field' ) ) {
    /**
     * Validate the VAT number field if present.
     */
    function buildbyhs_validate_vat_field() {
        if ( isset( $_POST['billing_vat_number'] ) && ! empty( $_POST['billing_vat_number'] ) ) {
            $vat = sanitize_text_field( wp_unslash( $_POST['billing_vat_number'] ) );
            // Simple format check: alphanumeric and length between 5-20
            if ( ! preg_match( '/^[A-Za-z0-9]{5,20}$/', $vat ) ) {
                wc_add_notice( __( 'Please enter a valid VAT number (5-20 alphanumeric characters).', 'buildbyhs' ), 'error' );
            }
        }
    }
}

if ( ! function_exists( 'buildbyhs_save_vat_field' ) ) {
    /**
     * Save the VAT number into order meta.
     *
     * @param int $order_id Order ID.
     */
    function buildbyhs_save_vat_field( $order_id ) {
        if ( isset( $_POST['billing_vat_number'] ) ) {
            $vat = sanitize_text_field( wp_unslash( $_POST['billing_vat_number'] ) );
            if ( ! empty( $vat ) ) {
                update_post_meta( $order_id, '_billing_vat_number', $vat );
            }
        }
    }
}
