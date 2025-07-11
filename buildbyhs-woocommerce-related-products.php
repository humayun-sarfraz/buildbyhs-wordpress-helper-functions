<?php
/**
 * File: buildbyhs-woocommerce-related-products.php
 * Function: buildbyhs_customize_woocommerce_related_products_args
 * Description: Customize the related products query arguments (number, columns, taxonomy) for WooCommerce.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-related-products.php';
 * add_filter( 'woocommerce_output_related_products_args', 'buildbyhs_customize_woocommerce_related_products_args', 20 );
 */

if ( ! function_exists( 'buildbyhs_customize_woocommerce_related_products_args' ) ) {
    /**
     * Adjust related products display settings.
     *
     * @param array $args Existing arguments: number, columns, post__not_in.
     * @return array Modified arguments.
     */
    function buildbyhs_customize_woocommerce_related_products_args( $args ) {
        // Default overrides
        $defaults = array(
            'posts_per_page' => 4,    // Number of related products
            'columns'        => 4,    // Columns in grid
            'orderby'        => 'rand', // Order by random or date
        );

        // Allow filters for customization
        $overrides = apply_filters( 'buildbyhs_related_products_overrides', $defaults );

        // Sanitize overrides
        $posts_per_page = isset( $overrides['posts_per_page'] ) ? intval( $overrides['posts_per_page'] ) : intval( $args['posts_per_page'] );
        $columns        = isset( $overrides['columns'] )        ? intval( $overrides['columns'] )        : intval( $args['columns'] );
        $orderby        = isset( $overrides['orderby'] )        ? sanitize_text_field( $overrides['orderby'] )    : sanitize_text_field( $args['orderby'] );

        // Merge with existing args
        $args['posts_per_page'] = $posts_per_page;
        $args['columns']        = $columns;
        $args['orderby']        = $orderby;

        return $args;
    }
}
