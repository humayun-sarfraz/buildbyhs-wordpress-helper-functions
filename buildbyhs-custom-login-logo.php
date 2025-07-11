<?php
/**
 * File: buildbyhs-custom-login-logo.php
 * Function: buildbyhs_login_logo_custom, buildbyhs_login_logo_url, buildbyhs_login_logo_title
 * Description: Replaces the default WordPress login logo, URL, and title with custom values,
 *              ensuring proper sanitization and escaping.
 *
 * Usage:
 * // In your theme or plugin:
 * add_action( 'login_enqueue_scripts', 'buildbyhs_login_logo_custom' );
 * add_filter( 'login_headerurl',       'buildbyhs_login_logo_url' );
 * add_filter( 'login_headertext',      'buildbyhs_login_logo_title' );
 */

if ( ! function_exists( 'buildbyhs_login_logo_custom' ) ) {
    /**
     * Enqueue custom CSS to replace the login logo.
     */
    function buildbyhs_login_logo_custom() {
        // Path to custom logo in theme directory
        $logo_path = get_stylesheet_directory_uri() . '/assets/images/login-logo.png';
        // Sanitize URL
        $logo_url  = esc_url( $logo_path );
        
        // Output inline CSS
        echo '<style type="text/css">'
            ."#login h1 a { background-image: url(' . $logo_url . ') !important; height:65px; width:auto; background-size:contain; }"
            ."body.login { background-color:#f1f1f1; }"
            .".login #backtoblog, .login #nav { text-align:center; }"
        .'</style>';
    }
}

if ( ! function_exists( 'buildbyhs_login_logo_url' ) ) {
    /**
     * Filter the login logo URL to point to the site home.
     *
     * @return string URL for the login header link.
     */
    function buildbyhs_login_logo_url() {
        $url = home_url();
        return esc_url( $url );
    }
}

if ( ! function_exists( 'buildbyhs_login_logo_title' ) ) {
    /**
     * Filter the login logo title attribute (hover text).
     *
     * @return string Text for the login header title attribute.
     */
    function buildbyhs_login_logo_title() {
        $title = get_bloginfo( 'name' );
        return esc_attr( $title );
    }
}
