<?php
/**
 * File: buildbyhs-woocommerce-product-search.php
 * Function: buildbyhs_modify_woocommerce_product_search
 * Description: Enhance product search to include SKU, product attributes, and custom fields.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-woocommerce-product-search.php';
 * add_action( 'pre_get_posts', 'buildbyhs_modify_woocommerce_product_search', 20 );
 */

if ( ! function_exists( 'buildbyhs_modify_woocommerce_product_search' ) ) {
    /**
     * Modify the main query on search to include SKU, attributes, and custom field matches for products.
     *
     * @param WP_Query $query The WP_Query instance (passed by reference).
     */
    function buildbyhs_modify_woocommerce_product_search( $query ) {
        if ( is_admin() || ! $query->is_search() || ! $query->is_main_query() ) {
            return;
        }

        // Only affect product searches
        $post_types = $query->get( 'post_type' );
        if ( empty( $post_types ) || ( is_string( $post_types ) && 'product' !== $post_types ) ) {
            return;
        }

        $search_term = $query->get( 's' );
        $search_term = sanitize_text_field( $search_term );
        if ( ! $search_term ) {
            return;
        }

        // Meta query for SKU and custom fields
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key'     => '_sku',
                'value'   => $search_term,
                'compare' => 'LIKE',
            ),
            array(
                'key'     => 'my_custom_field', // adjust field key as needed
                'value'   => $search_term,
                'compare' => 'LIKE',
            ),
        );

        // Tax query for attributes (pa_color, pa_size, etc.)
        $taxonomies = wc_get_attribute_taxonomies();
        $tax_query  = array( 'relation' => 'OR' );
        foreach ( $taxonomies as $tax ) {
            if ( empty( $tax->attribute_name ) ) {
                continue;
            }
            $taxonomy = 'pa_' . sanitize_key( $tax->attribute_name );
            $tax_query[] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'name',
                'terms'    => array( $search_term ),
                'operator' => 'LIKE',
            );
        }

        // Combine with existing query vars
        $query->set( 'meta_query', $meta_query );
        $query->set( 'tax_query', $tax_query );

        // Ensure title/content still search
        add_filter( 'posts_search', function( $search_sql ) use ( $search_term ) {
            global $wpdb;
            $like = '%' . $wpdb->esc_like( $search_term ) . '%';
            $search_parts = array();
            $search_parts[] = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s", $like );
            $search_parts[] = $wpdb->prepare( "{$wpdb->posts}.post_content LIKE %s", $like );
            return ' AND (' . implode( ' OR ', $search_parts ) . ') ';
        }, 10, 1 );
    }
}
