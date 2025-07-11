<?php
/**
 * File: buildbyhs-woocommerce-admin-order-columns.php
 * Functions: buildbyhs_add_order_columns, buildbyhs_render_order_columns
 * Description: Adds and renders custom columns (Delivery Date & Gift Message) in the WooCommerce Orders admin list.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-admin-order-columns.php';
 * add_filter( 'manage_edit-shop_order_columns',      'buildbyhs_add_order_columns', 20 );
 * add_action( 'manage_shop_order_posts_custom_column','buildbyhs_render_order_columns', 20, 2 );
 */

if ( ! function_exists( 'buildbyhs_add_order_columns' ) ) {
    /**
     * Add custom columns to the Orders list.
     *
     * @param array $columns Existing columns.
     * @return array Modified columns.
     */
    function buildbyhs_add_order_columns( $columns ) {
        $new = array();
        foreach ( $columns as $key => $label ) {
            $new[ $key ] = $label;
            if ( 'order_total' === $key ) {
                $new['buildbyhs_delivery_date'] = __( 'Delivery Date', 'buildbyhs' );
                $new['buildbyhs_gift_message']  = __( 'Gift Message',  'buildbyhs' );
            }
        }
        return $new;
    }
}

if ( ! function_exists( 'buildbyhs_render_order_columns' ) ) {
    /**
     * Render data for custom order columns.
     *
     * @param string $column Column slug.
     * @param int    $post_id Order post ID.
     */
    function buildbyhs_render_order_columns( $column, $post_id ) {
        if ( 'buildbyhs_delivery_date' === $column ) {
            $date = get_post_meta( $post_id, '_buildbyhs_delivery_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
            } else {
                echo '&ndash;';
            }
        }
        if ( 'buildbyhs_gift_message' === $column ) {
            $message = get_post_meta( $post_id, '_buildbyhs_gift_message', true );
            if ( $message ) {
                echo esc_html( wp_trim_words( $message, 5, '...' ) );
            } else {
                echo '&ndash;';
            }
        }
    }
}
