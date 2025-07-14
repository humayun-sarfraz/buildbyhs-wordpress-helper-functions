<?php
/**
 * File: buildbyhs-custom-login-logo-1.php
 * Class: BuildByHS_Custom_Login_Logo
 * Description: Replaces the WordPress login logo with a custom image, and optionally adjusts the login form URL and title.
 *
 * Usage:
 * require_once get_stylesheet_directory() . '/buildbyhs-custom-login-logo.php';
 * // In theme or plugin init:
 * BuildByHS_Custom_Login_Logo::init( array(
 *     'logo_url'      => get_stylesheet_directory_uri() . '/assets/images/custom-login-logo.png',
 *     'logo_width'    => 200,
 *     'logo_height'   => 60,
 *     'home_url'      => home_url(),
 *     'home_title'    => get_bloginfo( 'name' ),
 * ) );
 */

if ( ! class_exists( 'BuildByHS_Custom_Login_Logo' ) ) {
    class BuildByHS_Custom_Login_Logo {
        protected static $args = array();

        /**
         * Initialize with custom arguments and register hooks.
         *
         * @param array $args {
         *   @type string 'logo_url'    URL to custom logo image.
         *   @type int    'logo_width'  Width in px.
         *   @type int    'logo_height' Height in px.
         *   @type string 'home_url'    URL to link logo to.
         *   @type string 'home_title'  Title attribute for logo link.
         * }
         */
        public static function init( $args = array() ) {
            $defaults = array(
                'logo_url'    => '',
                'logo_width'  => 80,
                'logo_height' => 80,
                'home_url'    => home_url(),
                'home_title'  => get_bloginfo( 'name' ),
            );
            self::$args = wp_parse_args( $args, $defaults );

            add_action( 'login_enqueue_scripts', array( __CLASS__, 'enqueue_custom_css' ) );
            add_filter( 'login_headerurl',    array( __CLASS__, 'filter_logo_url' ) );
            add_filter( 'login_headertext',   array( __CLASS__, 'filter_logo_title' ) );
        }

        /**
         * Output inline CSS to override the login logo.
         */
        public static function enqueue_custom_css() {
            if ( empty( self::$args['logo_url'] ) ) {
                return;
            }
            $url    = esc_url( self::$args['logo_url'] );
            $width  = intval( self::$args['logo_width'] );
            $height = intval( self::$args['logo_height'] );
            $css = sprintf(
                "#login h1 a { background-image: url('%s'); width: %dpx; height: %dpx; background-size: contain; }",
                $url, $width, $height
            );
            wp_add_inline_style( 'login', $css );
        }

        /**
         * Change the logo link URL.
         *
         * @return string
         */
        public static function filter_logo_url() {
            return esc_url( self::$args['home_url'] );
        }

        /**
         * Change the logo title attribute.
         *
         * @return string
         */
        public static function filter_logo_title() {
            return esc_attr( self::$args['home_title'] );
        }
    }
}
