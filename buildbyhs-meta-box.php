<?php
/**
 * File: buildbyhs-meta-box.php
 * Functions: buildbyhs_register_meta_box, buildbyhs_meta_box_callback, buildbyhs_save_meta_box_data
 * Description: Registers a custom meta box on post edit screens with secure nonce handling,
 *              sanitization, and escaping.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-meta-box.php';
 */

if ( ! function_exists( 'buildbyhs_register_meta_box' ) ) {
    /**
     * Hook into add_meta_boxes to register the custom meta box.
     */
    function buildbyhs_register_meta_box() {
        add_meta_box(
            'buildbyhs_custom_meta',          // ID
            __( 'Custom Data', 'buildbyhs' ), // Title
            'buildbyhs_meta_box_callback',    // Callback
            'post',                           // Screen (post type)
            'side',                           // Context
            'default'                         // Priority
        );
    }
    add_action( 'add_meta_boxes', 'buildbyhs_register_meta_box' );
}

if ( ! function_exists( 'buildbyhs_meta_box_callback' ) ) {
    /**
     * Renders the meta box HTML.
     *
     * @param WP_Post $post The post object.
     */
    function buildbyhs_meta_box_callback( $post ) {
        // Add nonce field for security
        wp_nonce_field( 'buildbyhs_meta_box', 'buildbyhs_meta_box_nonce' );

        // Retrieve existing value
        $value = get_post_meta( $post->ID, '_buildbyhs_meta_key', true );
        $value = esc_html( $value );

        // Output label and input
        echo '<label for="buildbyhs_meta_field">' . esc_html__( 'Enter custom data:', 'buildbyhs' ) . '</label>'; 
        echo '<input type="text" id="buildbyhs_meta_field" name="buildbyhs_meta_field" value="' . $value . '" class="widefat" />';
    }
}

if ( ! function_exists( 'buildbyhs_save_meta_box_data' ) ) {
    /**
     * Save the meta box data when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    function buildbyhs_save_meta_box_data( $post_id ) {
        // Verify nonce
        if ( ! isset( $_POST['buildbyhs_meta_box_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['buildbyhs_meta_box_nonce'] ), 'buildbyhs_meta_box' ) ) {
            return;
        }

        // Check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check user permissions
        if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        // Sanitize and save
        if ( isset( $_POST['buildbyhs_meta_field'] ) ) {
            $new_value = sanitize_text_field( wp_unslash( $_POST['buildbyhs_meta_field'] ) );
            update_post_meta( $post_id, '_buildbyhs_meta_key', $new_value );
        }
    }
    add_action( 'save_post', 'buildbyhs_save_meta_box_data' );
}
