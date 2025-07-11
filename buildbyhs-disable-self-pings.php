<?php
/**
 * File: buildbyhs-disable-self-pings.php
 * Function: buildbyhs_disable_self_pings
 * Description: Prevents WordPress from creating pingbacks/self-trackbacks when linking to your own domain.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-disable-self-pings.php';
 * add_action( 'pre_ping', 'buildbyhs_disable_self_pings' );
 */

if ( ! function_exists( 'buildbyhs_disable_self_pings' ) ) {
    /**
     * Removes links to own domain from ping list to avoid self pings.
     *
     * @param array &$links List of URLs to ping.
     */
    function buildbyhs_disable_self_pings( &$links ) {
        $home_url = get_home_url();
        foreach ( $links as $l => $link ) {
            if ( 0 === strpos( $link, $home_url ) ) {
                unset( $links[ $l ] );
            }
        }
    }
}
