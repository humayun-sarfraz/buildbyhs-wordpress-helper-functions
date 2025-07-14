<?php
/**
 * File: buildbyhs-admin-notice.php
 * Class: BuildByHS_Admin_Notice
 * Description: Registers a dismissible admin notice that can be customized via filters, using user meta to remember dismissal.
 *
 * Usage:
 * require_once get_stylesheet_directory() . '/buildbyhs-admin-notice.php';
 * // Hook in your plugin to register a notice:
 * add_action( 'admin_init', function() {
 *     BuildByHS_Admin_Notice::add_notice( 'welcome_notice', array(
 *         'message'   => __( 'Welcome to the dashboard! Check out our <a href="/help">help](https://example.com/help)', 'buildbyhs' ),
 *         'type'      => 'success', // 'success', 'error', 'warning', 'info'
 *         'capability'=> 'manage_options',
 *         'dismiss_expiration' => WEEK_IN_SECONDS,
 *     ) );
 * } );
 */

if ( ! class_exists( 'BuildByHS_Admin_Notice' ) ) {
    class BuildByHS_Admin_Notice {
        /**
         * Adds a new dismissible notice.
         *
         * @param string $id          Unique notice ID.
         * @param array  $args        Arguments: 'message' (HTML-safe), 'type','capability','dismiss_expiration'.
         */
        public static function add_notice( $id, $args ) {
            $defaults = array(
                'message'           => '',
                'type'              => 'info',
                'capability'        => 'manage_options',
                'dismiss_expiration' => DAY_IN_SECONDS * 7,
            );
            $args = wp_parse_args( $args, $defaults );

            // Only show if user has capability and hasn't dismissed
            if ( ! current_user_can( $args['capability'] ) ) {
                return;
            }
            $user_id = get_current_user_id();
            $transient = 'buildbyhs_notice_' . $id . '_dismissed_' . $user_id;
            if ( get_transient( $transient ) ) {
                return;
            }

            // Hook display and AJAX dismissal
            add_action( 'admin_notices', function() use ( $id, $args, $transient ) {
                printf(
                    '<div class="notice notice-%1$s is-dismissible buildbyhs-notice-%2$s"><p>%3$s</p></div>',
                    esc_attr( $args['type'] ),
                    esc_attr( $id ),
                    wp_kses_post( $args['message'] )
                );
                // Enqueue script to record dismissal
                add_action( 'admin_footer', function() use ( $id, $transient, $args ) {
                    ?>
                    <script>
                    (function($){
                        $(document).on('click', '.buildbyhs-notice-<?php echo esc_js( $id ); ?> .notice-dismiss', function(){
                            $.post(ajaxurl, {
                                action: 'buildbyhs_dismiss_notice',
                                notice_id: '<?php echo esc_js( $id ); ?>',
                                _wpnonce: '<?php echo esc_js( wp_create_nonce( 'buildbyhs_dismiss_' . $id ) ); ?>'
                            });
                        });
                    })(jQuery);
                    </script>
                    <?php
                });

                // Register AJAX handler
                add_action( 'wp_ajax_buildbyhs_dismiss_notice', function() use ( $id, $transient, $args ) {
                    check_ajax_referer( 'buildbyhs_dismiss_' . $id, '_wpnonce' );
                    set_transient( $transient, 1, intval( $args['dismiss_expiration'] ) );
                    wp_send_json_success();
                } );
            });
        }
    }
}
