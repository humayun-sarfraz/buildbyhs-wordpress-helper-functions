<?php
/**
 * File: buildbyhs-woocommerce-product-tabs.php
 * Function: buildbyhs_add_custom_product_tab
 * Description: Adds a custom product tab to WooCommerce single product pages,
 *              pulling content from a custom field or providing static content.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-product-tabs.php';
 * add_filter( 'woocommerce_product_tabs', 'buildbyhs_add_custom_product_tab', 98 );
 */

if ( ! function_exists( 'buildbyhs_add_custom_product_tab' ) ) {
    /**
     * Add a custom tab to the product data tabs.
     *
     * @param array $tabs Existing tabs.
     * @return array Modified tabs.
     */
    function buildbyhs_add_custom_product_tab( $tabs ) {
        global $post;

        // Fetch tab title and content from product meta (custom fields)
        $tab_title   = get_post_meta( $post->ID, '_buildbyhs_tab_title', true );
        $tab_content = get_post_meta( $post->ID, '_buildbyhs_tab_content', true );

        // Fallback defaults
        $tab_title   = $tab_title ? sanitize_text_field( $tab_title ) : __( 'Additional Info', 'buildbyhs' );
        $tab_content = wp_kses_post( $tab_content ?: __( 'No additional information available.', 'buildbyhs' ) );

        // Add the tab
        $tabs['buildbyhs_additional_info'] = array(
            'title'    => $tab_title,
            'priority' => 50,
            'callback' => 'buildbyhs_product_tab_content',
        );

        return $tabs;
    }
}

if ( ! function_exists( 'buildbyhs_product_tab_content' ) ) {
    /**
     * Output content for the custom product tab.
     */
    function buildbyhs_product_tab_content() {
        global $post;
        $content = get_post_meta( $post->ID, '_buildbyhs_tab_content', true );
        echo '<div class="buildbyhs-product-tab">' . wp_kses_post( $content ) . '</div>';
    }
}
