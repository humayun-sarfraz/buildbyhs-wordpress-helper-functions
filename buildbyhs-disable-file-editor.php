<?php
/**
 * File: buildbyhs-disable-file-editor.php
 * Function: buildbyhs_disable_file_editor
 * Description: Disables the built-in theme and plugin file editors in WordPress for improved security.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-file-editor.php';
 * add_action( 'admin_init', 'buildbyhs_disable_file_editor' );
 */

if ( ! function_exists( 'buildbyhs_disable_file_editor' ) ) {
    /**
     * Define constants to disable file editing and automatic updates.
     */
    function buildbyhs_disable_file_editor() {
        // Prevent file edits via the dashboard
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
        // Prevent plugin and theme update/installation via dashboard
        if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
            define( 'DISALLOW_FILE_MODS', true );
        }
    }
}
