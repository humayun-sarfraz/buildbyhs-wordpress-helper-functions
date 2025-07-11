<?php
/**
 * File: buildbyhs-disable-updates.php
 * Functions: buildbyhs_disable_auto_updates, buildbyhs_disable_plugin_updates, buildbyhs_disable_theme_updates
 * Description: Disables automatic WordPress core, plugin, and theme updates,
 *              providing filters to override per type and improving control.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-updates.php';
 * add_filter( 'auto_update_core',        'buildbyhs_disable_auto_updates' );
 * add_filter( 'auto_update_plugin',      'buildbyhs_disable_plugin_updates', 10, 2 );
 * add_filter( 'auto_update_theme',       'buildbyhs_disable_theme_updates',  10, 2 );
 */

if ( ! function_exists( 'buildbyhs_disable_auto_updates' ) ) {
    /**
     * Disable all core auto updates by default.
     *
     * @param bool|string $update Current auto update setting.
     * @return bool False to disable.
     */
    function buildbyhs_disable_auto_updates( $update ) {
        return false;
    }
}

if ( ! function_exists( 'buildbyhs_disable_plugin_updates' ) ) {
    /**
     * Disable automatic updates for plugins.
     *
     * @param bool   $update  Whether to update.
     * @param object $item    Plugin update offer object.
     * @return bool False to disable plugin auto updates.
     */
    function buildbyhs_disable_plugin_updates( $update, $item ) {
        return false;
    }
}

if ( ! function_exists( 'buildbyhs_disable_theme_updates' ) ) {
    /**
     * Disable automatic updates for themes.
     *
     * @param bool   $update  Whether to update.
     * @param object $item    Theme update offer object.
     * @return bool False to disable theme auto updates.
     */
    function buildbyhs_disable_theme_updates( $update, $item ) {
        return false;
    }
}
