<?php
/**
 * File: buildbyhs-woocommerce-mini-cart-refresh.php
 * Function: buildbyhs_woocommerce_mini_cart_fragments
 * Description: Updates WooCommerce mini-cart fragments via AJAX after cart modifications,
 *              refreshing the cart count and mini-cart content without full page reload.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-mini-cart-refresh.php';
 * add_filter( 'woocommerce_add_to_cart_fragments', 'buildbyhs_woocommerce_mini_cart_fragments' );
 */

if ( ! function_exists( 'buildbyhs_woocommerce_mini_cart_fragments' ) ) {
    /**
     * Refresh mini-cart fragments (cart link and dropdown) via AJAX.
     *
     * @param array $fragments Existing fragments to update.
     * @return array Modified fragments including updated cart count and mini-cart content.
     */
    function buildbyhs_woocommerce_mini_cart_fragments( $fragments ) {
        // Cart link with updated count
        ob_start();
        ?>
        <a class="buildbyhs-mini-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
            <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        </a>
        <?php
        $fragments['a.buildbyhs-mini-cart-link'] = ob_get_clean();

        // Mini-cart dropdown contents
        ob_start();
        ?>
        <div class="widget_shopping_cart_content">
            <?php woocommerce_mini_cart(); ?>
        </div>
        <?php
        $fragments['div.widget_shopping_cart_content'] = ob_get_clean();

        return $fragments;
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'buildbyhs_woocommerce_mini_cart_fragments' );
}
