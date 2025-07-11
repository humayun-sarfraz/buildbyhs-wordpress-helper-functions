<?php
/**
 * File: buildbyhs-register-post-type.php
 * Function: buildbyhs_register_post_type
 * Description: Simplifies registration of a custom post type with sensible defaults,
 *              proper sanitization, escaping, and translation-ready labels.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-register-post-type.php';
 * add_action( 'init', function() {
 *     buildbyhs_register_post_type( 'book', array(
 *         'singular' => __( 'Book', 'buildbyhs' ),
 *         'plural'   => __( 'Books', 'buildbyhs' ),
 *         'slug'     => 'books',
 *         'supports' => array( 'title', 'editor', 'thumbnail' ),
 *     ) );
 * } );
 */

if ( ! function_exists( 'buildbyhs_register_post_type' ) ) {
    /**
     * Registers a custom post type with provided label parameters.
     *
     * @param string $type     Machine name for the post type (no spaces).
     * @param array  $options  Options array:
     *                         'singular' => Singular label (string, required).
     *                         'plural'   => Plural label (string, required).
     *                         'slug'     => URL slug (string, optional, defaults to type).
     *                         'supports' => Array of features to support (optional).
     *                         'args'     => Additional register_post_type args (optional).
     */
    function buildbyhs_register_post_type( $type, $options = array() ) {
        // Sanitize type
        $type_key = sanitize_key( $type );
        if ( empty( $type_key ) ) {
            return;
        }

        // Required labels
        $singular = isset( $options['singular'] ) ? sanitize_text_field( $options['singular'] ) : '';
        $plural   = isset( $options['plural'] )   ? sanitize_text_field( $options['plural'] )   : '';
        if ( ! $singular || ! $plural ) {
            return;
        }

        // Slug
        $slug = isset( $options['slug'] ) ? sanitize_title( $options['slug'] ) : $type_key;

        // Supports
        $supports = isset( $options['supports'] ) && is_array( $options['supports'] )
            ? array_map( 'sanitize_key', $options['supports'] )
            : array( 'title', 'editor' );

        // Base labels
        $labels = array(
            'name'                  => $plural,
            'singular_name'         => $singular,
            'menu_name'             => $plural,
            'name_admin_bar'        => $singular,
            'add_new'               => sprintf( __( 'Add New %s', 'buildbyhs' ), $singular ),
            'add_new_item'          => sprintf( __( 'Add New %s', 'buildbyhs' ), $singular ),
            'new_item'              => sprintf( __( 'New %s', 'buildbyhs' ), $singular ),
            'edit_item'             => sprintf( __( 'Edit %s', 'buildbyhs' ), $singular ),
            'view_item'             => sprintf( __( 'View %s', 'buildbyhs' ), $singular ),
            'all_items'             => sprintf( __( 'All %s', 'buildbyhs' ), $plural ),
            'search_items'          => sprintf( __( 'Search %s', 'buildbyhs' ), $plural ),
            'not_found'             => sprintf( __( 'No %s found.', 'buildbyhs' ), strtolower( $plural ) ),
            'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'buildbyhs' ), strtolower( $plural ) ),
        );

        // Default args
        $defaults = array(
            'labels'             => $labels,
            'public'             => true,
            'show_in_menu'       => true,
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => $slug ),
            'supports'           => $supports,
            'show_in_rest'       => true,
        );

        // Merge additional args
        $args = isset( $options['args'] ) && is_array( $options['args'] )
            ? wp_parse_args( $options['args'], $defaults )
            : $defaults;

        register_post_type( $type_key, $args );
    }
}
