<?php
/**
 * File: buildbyhs-register-taxonomy.php
 * Function: buildbyhs_register_taxonomy
 * Description: Simplifies registration of a custom taxonomy with sensible defaults,
 *              proper sanitization, escaping, and translation-ready labels.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-register-taxonomy.php';
 * add_action( 'init', function() {
 *     buildbyhs_register_taxonomy( 'genre', 'book', array(
 *         'singular'   => __( 'Genre', 'buildbyhs' ),
 *         'plural'     => __( 'Genres', 'buildbyhs' ),
 *         'slug'       => 'genres',
 *         'hierarchical' => true,
 *     ) );
 * } );
 */

if ( ! function_exists( 'buildbyhs_register_taxonomy' ) ) {
    /**
     * Registers a custom taxonomy with provided label parameters.
     *
     * @param string       $taxonomy    Machine name for the taxonomy (no spaces).
     * @param string|array $object_type Object type(s) to attach taxonomy to.
     * @param array        $options     Options array:
     *                                  'singular'     => Singular label (string, required).
     *                                  'plural'       => Plural label (string, required).
     *                                  'slug'         => URL slug (string, optional, defaults to taxonomy).
     *                                  'hierarchical' => bool (optional, defaults to false).
     *                                  'args'         => Additional register_taxonomy args (optional).
     */
    function buildbyhs_register_taxonomy( $taxonomy, $object_type, $options = array() ) {
        $tax_key = sanitize_key( $taxonomy );
        if ( empty( $tax_key ) ) {
            return;
        }

        // Required labels
        $singular = isset( $options['singular'] ) ? sanitize_text_field( $options['singular'] ) : '';
        $plural   = isset( $options['plural'] )   ? sanitize_text_field( $options['plural'] )   : '';
        if ( ! $singular || ! $plural ) {
            return;
        }

        // Slug
        $slug = isset( $options['slug'] ) ? sanitize_title( $options['slug'] ) : $tax_key;

        // Hierarchical flag
        $hierarchical = ! empty( $options['hierarchical'] );

        // Labels
        $labels = array(
            'name'              => $plural,
            'singular_name'     => $singular,
            'search_items'      => sprintf( __( 'Search %s', 'buildbyhs' ), $plural ),
            'all_items'         => sprintf( __( 'All %s', 'buildbyhs' ), $plural ),
            'parent_item'       => $hierarchical ? sprintf( __( 'Parent %s', 'buildbyhs' ), $singular ) : '',
            'parent_item_colon' => $hierarchical ? sprintf( __( 'Parent %s:', 'buildbyhs' ), $singular ) : '',
            'edit_item'         => sprintf( __( 'Edit %s', 'buildbyhs' ), $singular ),
            'update_item'       => sprintf( __( 'Update %s', 'buildbyhs' ), $singular ),
            'add_new_item'      => sprintf( __( 'Add New %s', 'buildbyhs' ), $singular ),
            'new_item_name'     => sprintf( __( 'New %s Name', 'buildbyhs' ), $singular ),
            'menu_name'         => $plural,
        );

        // Default args
        $defaults = array(
            'labels'            => $labels,
            'hierarchical'      => $hierarchical,
            'public'            => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => $slug ),
        );

        // Merge additional args
        $args = isset( $options['args'] ) && is_array( $options['args'] )
            ? wp_parse_args( $options['args'], $defaults )
            : $defaults;

        register_taxonomy( $tax_key, $object_type, $args );
    }
}
