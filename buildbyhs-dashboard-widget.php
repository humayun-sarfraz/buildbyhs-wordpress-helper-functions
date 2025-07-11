<?php
/**
 * File: buildbyhs-dashboard-widget.php
 * Function: buildbyhs_add_custom_dashboard_widget, buildbyhs_dashboard_widget_display
 * Description: Adds a custom widget to the WordPress admin dashboard,
 *              allowing display of quick info or links.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-dashboard-widget.php';
 */

if ( ! function_exists( 'buildbyhs_add_custom_dashboard_widget' ) ) {
    /**
     * Register the dashboard widget.
     */
    function buildbyhs_add_custom_dashboard_widget() {
        wp_add_dashboard_widget(
            'buildbyhs_dashboard_widget',              // Widget slug.
            __('Quick Links & Info', 'buildbyhs'),    // Title.
            'buildbyhs_dashboard_widget_display'       // Display callback.
        );
    }
    add_action( 'wp_dashboard_setup', 'buildbyhs_add_custom_dashboard_widget' );
}

if ( ! function_exists( 'buildbyhs_dashboard_widget_display' ) ) {
    /**
     * Output the content of the dashboard widget.
     */
    function buildbyhs_dashboard_widget_display() {
        // Example: list of quick links and a summary
        $home_url = esc_url( home_url() );
        $posts_count = number_format_i18n( wp_count_posts()->publish );
        $comments_count = number_format_i18n( wp_count_comments()->approved );
        ?>
        <p><?php printf( esc_html__( 'Published Posts: %s', 'buildbyhs' ), esc_html( $posts_count ) ); ?></p>
        <p><?php printf( esc_html__( 'Approved Comments: %s', 'buildbyhs' ), esc_html( $comments_count ) ); ?></p>
        <ul>
            <li><a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Add New Post', 'buildbyhs' ); ?></a></li>
            <li><a href="<?php echo esc_url( admin_url( 'edit-comments.php' ) ); ?>"><?php esc_html_e( 'Moderate Comments', 'buildbyhs' ); ?></a></li>
            <li><a href="<?php echo esc_url( $home_url ); ?>"><?php esc_html_e( 'View Site', 'buildbyhs' ); ?></a></li>
        </ul>
        <?php
    }
}
