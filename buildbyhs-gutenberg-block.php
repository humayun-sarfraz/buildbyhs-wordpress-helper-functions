<?php
/**
 * File: buildbyhs-gutenberg-block.php
 * Function: buildbyhs_register_custom_block
 * Description: Boilerplate to register a custom Gutenberg block with editor scripts and styles,
 *              ensuring proper sanitization and escaping.
 *
 * Usage:
 * // In your plugin or theme's functions.php:
 * require_once get_stylesheet_directory() . '/buildbyhs-gutenberg-block.php';
 * add_action( 'init', 'buildbyhs_register_custom_block' );
 */

if ( ! function_exists( 'buildbyhs_register_custom_block' ) ) {
    function buildbyhs_register_custom_block() {
        // 1) Register block editor script
        wp_register_script(
            'buildbyhs-block-editor',
            get_stylesheet_directory_uri() . '/assets/js/block-editor.js', // Adjust path
            array( 'wp-blocks', 'wp-element', 'wp-editor' ),
            filemtime( get_stylesheet_directory() . '/assets/js/block-editor.js' ),
            true
        );

        // 2) Register block editor styles
        wp_register_style(
            'buildbyhs-block-editor-style',
            get_stylesheet_directory_uri() . '/assets/css/block-editor.css',
            array( 'wp-edit-blocks' ),
            filemtime( get_stylesheet_directory() . '/assets/css/block-editor.css' )
        );

        // 3) Register front-end styles
        wp_register_style(
            'buildbyhs-block-style',
            get_stylesheet_directory_uri() . '/assets/css/block-style.css',
            array(),
            filemtime( get_stylesheet_directory() . '/assets/css/block-style.css' )
        );

        // 4) Register the block type
        register_block_type( 'buildbyhs/custom-block', array(
            'editor_script'   => 'buildbyhs-block-editor',
            'editor_style'    => 'buildbyhs-block-editor-style',
            'style'           => 'buildbyhs-block-style',
            'render_callback' => 'buildbyhs_custom_block_render',
            'attributes'      => array(
                'content' => array(
                    'type'    => 'string',
                    'default' => ''
                ),
            ),
        ) );
    }
}

if ( ! function_exists( 'buildbyhs_custom_block_render' ) ) {
    /**
     * Server-side render callback for the custom block.
     *
     * @param array $attributes Block attributes.
     * @return string HTML output.
     */
    function buildbyhs_custom_block_render( $attributes ) {
        $content = isset( $attributes['content'] ) ? sanitize_text_field( $attributes['content'] ) : '';
        return '<div class="buildbyhs-custom-block">' . esc_html( $content ) . '</div>';
    }
}
