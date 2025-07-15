<?php
/**
 * File: buildbyhs-shortcode-recent-orders.php
 * Function: buildbyhs_recent_orders_shortcode
 * Description: Provides a [recent_orders] shortcode to display a logged-in customer's recent WooCommerce orders.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-shortcode-recent-orders.php';
 * // Then in content or template:
 * echo do_shortcode( '[recent_orders count="5" status="completed"]' );
 */

if ( ! function_exists( 'buildbyhs_recent_orders_shortcode' ) ) {
    /**
     * Shortcode handler to output recent orders for the current user.
     *
     * @param array $atts {
     *     Shortcode attributes.
     *     @type int    $count  Number of orders to display. Default 5.
     *     @type string $status Comma-separated order statuses to include. Default 'completed'.
     * }
     * @return string HTML list of orders or message.
     */
    function buildbyhs_recent_orders_shortcode( $atts ) {
        if ( ! function_exists( 'wc_get_orders' ) ) {
            return '<p>' . esc_html__( 'WooCommerce is not active.', 'buildbyhs' ) . '</p>';
        }

        // Ensure user is logged in
        if ( ! is_user_logged_in() ) {
            return '<p>' . esc_html__( 'You must be logged in to view your orders.', 'buildbyhs' ) . '</p>';
        }

        $defaults = array(
            'count'  => 5,
            'status' => 'completed',
        );
        $atts = shortcode_atts( $defaults, $atts, 'recent_orders' );

        $count  = absint( $atts['count'] );
        $status = sanitize_text_field( $atts['status'] );
        $statuses = array_map( 'sanitize_key', array_filter( array_map( 'trim', explode( ',', $status ) ) ) );
        if ( empty( $statuses ) ) {
            $statuses = array( 'completed' );
        }

        $customer_id = get_current_user_id();

        $orders = wc_get_orders( array(
            'customer_id' => $customer_id,
            'limit'       => $count,
            'status'      => $statuses,
            'orderby'     => 'date',
            'order'       => 'DESC',
        ) );

        if ( empty( $orders ) ) {
            return '<p>' . esc_html__( 'No recent orders found.', 'buildbyhs' ) . '</p>';
        }

        ob_start();
        ?>
        <ul class="buildbyhs-recent-orders">
            <?php foreach ( $orders as $order ) : ?>
                <li>
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                        <?php
                        printf(
                            /* translators: 1: order number, 2: date */
                            esc_html__( 'Order #%1$s placed on %2$s', 'buildbyhs' ),
                            esc_html( $order->get_order_number() ),
                            esc_html( wc_format_datetime( $order->get_date_created() ) )
                        );
                        ?>
                    </a>
                    <span class="order-status"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
                    <span class="order-total"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }
    add_shortcode( 'recent_orders', 'buildbyhs_recent_orders_shortcode' );
}
