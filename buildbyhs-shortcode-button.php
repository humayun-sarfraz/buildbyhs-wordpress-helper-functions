<?php
/**
 * File: buildbyhs-shortcode-button.php
 * Function: buildbyhs_register_shortcode_button
 * Description: Adds a TinyMCE button that wraps selected text in a [callout] shortcode,
 *              with proper sanitization and escaping and inline JS injection.
 *
 * Usage:
 * // In your theme or plugin's main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-shortcode-button.php';
 */

if ( ! function_exists( 'buildbyhs_register_shortcode_button' ) ) {
    /**
     * Register the shortcode button in TinyMCE toolbar.
     */
    function buildbyhs_register_shortcode_button() {
        if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) && 'true' === get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_buttons', 'buildbyhs_add_shortcode_button' );
            add_action( 'admin_footer', 'buildbyhs_print_shortcode_button_script' );
        }
    }
    add_action( 'admin_head', 'buildbyhs_register_shortcode_button' );
}

if ( ! function_exists( 'buildbyhs_add_shortcode_button' ) ) {
    /**
     * Add the callout button to the TinyMCE toolbar.
     *
     * @param array $buttons Existing TinyMCE buttons.
     * @return array Modified buttons.
     */
    function buildbyhs_add_shortcode_button( $buttons ) {
        $buttons[] = 'buildbyhs_callout';
        return $buttons;
    }
}

if ( ! function_exists( 'buildbyhs_print_shortcode_button_script' ) ) {
    /**
     * Outputs the inline JavaScript to define the TinyMCE plugin for the callout button.
     */
    function buildbyhs_print_shortcode_button_script() {
        // Only output on post edit screens with rich editing
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
        ?>
        <script type="text/javascript">
        (function() {
            tinymce.PluginManager.add('buildbyhs_callout', function(editor) {
                editor.addButton('buildbyhs_callout', {
                    text: 'Callout',
                    icon: false,
                    onclick: function() {
                        var selected = editor.selection.getContent({ format: 'html' });
                        var content = selected ? '[callout]' + selected + '[/callout]' : '[callout][/callout]';
                        editor.execCommand('mceInsertContent', false, content);
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
