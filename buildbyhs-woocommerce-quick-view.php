<?php
/**
 * File: buildbyhs-woocommerce-quick-view.php
 * Functions: buildbyhs_enqueue_quick_view_assets, buildbyhs_add_quick_view_button, buildbyhs_handle_quick_view_ajax
 * Description: Provides a Quick View modal for products on shop and archive pages via AJAX,
 *              loading product title, image, price, and add-to-cart form.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-quick-view.php';
 * add_action( 'wp_enqueue_scripts', 'buildbyhs_enqueue_quick_view_assets' );
 * add_action( 'woocommerce_after_shop_loop_item', 'buildbyhs_add_quick_view_button', 15 );
 * add_action( 'wp_ajax_buildbyhs_quick_view', 'buildbyhs_handle_quick_view_ajax' );
 * add_action( 'wp_ajax_nopriv_buildbyhs_quick_view', 'buildbyhs_handle_quick_view_ajax' );
 */

if ( ! function_exists( 'buildbyhs_enqueue_quick_view_assets' ) ) {
    /**
     * Enqueue CSS and JS for Quick View functionality.
     */
    function buildbyhs_enqueue_quick_view_assets() {
        wp_enqueue_style( 'buildbyhs-quick-view-style', get_stylesheet_directory_uri() . '/assets/css/quick-view.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/quick-view.css' ) );
        wp_enqueue_script( 'buildbyhs-quick-view-script', get_stylesheet_directory_uri() . '/assets/js/quick-view.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/quick-view.js' ), true );
        wp_localize_script( 'buildbyhs-quick-view-script', 'buildbyhsQV', array(
            'ajaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
            'nonce'   => wp_create_nonce( 'buildbyhs_quick_view_nonce' ),
        ) );
    }
}

if ( ! function_exists( 'buildbyhs_add_quick_view_button' ) ) {
    /**
     * Output the Quick View button on each product in loops.
     */
    function buildbyhs_add_quick_view_button() {
        global $product;
        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            return;
        }
        $id = esc_attr( $product->get_id() );
        echo '<button class="buildbyhs-quick-view-button" data-product_id="' . $id . '">' . esc_html__( 'Quick View', 'buildbyhs' ) . '</button>';
    }
}

if ( ! function_exists( 'buildbyhs_handle_quick_view_ajax' ) ) {
    /**
     * AJAX handler to load product snippet for Quick View.
     */
    function buildbyhs_handle_quick_view_ajax() {
        check_ajax_referer( 'buildbyhs_quick_view_nonce', 'nonce' );
        $pid = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

        if ( ! $pid ) {
            wp_send_json_error();
        }

        $product = wc_get_product( $pid );
        if ( ! $product ) {
            wp_send_json_error();
        }

        ob_start();
        ?>
        <div class="buildbyhs-quick-view-content">
            <h2><?php echo esc_html( $product->get_name() ); ?></h2>
            <div class="buildbyhs-quick-view-image"><?php echo $product->get_image(); ?></div>
            <div class="buildbyhs-quick-view-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success( array( 'html' => $html ) );
    }
}
