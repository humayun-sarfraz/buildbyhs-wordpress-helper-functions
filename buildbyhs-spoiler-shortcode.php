<?php
/**
 * File: buildbyhs-spoiler-shortcode.php
 * Function: buildbyhs_register_spoiler_shortcode, buildbyhs_spoiler_shortcode
 * Description: Registers a [spoiler] shortcode that hides content behind a toggle,
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // In your theme or plugin main file:
 * require_once get_stylesheet_directory() . '/buildbyhs-spoiler-shortcode.php';
 *
 * // Usage in content:
 * echo do_shortcode( '[spoiler title="Click to reveal"]Secret content here[/spoiler]' );
 */

if ( ! function_exists( 'buildbyhs_spoiler_shortcode' ) ) {
    /**
     * Processes the [spoiler] shortcode.
     *
     * @param array  $atts    Shortcode attributes.
     * @param string $content Enclosed content.
     * @return string HTML markup for spoiler box.
     */
    function buildbyhs_spoiler_shortcode( $atts, $content = '' ) {
        // Sanitize and extract attributes
        $atts = shortcode_atts(
            array(
                'title' => __( 'Spoiler', 'buildbyhs' ),
            ), $atts, 'spoiler'
        );
        $title   = sanitize_text_field( $atts['title'] );
        $content = wp_kses_post( $content );

        // Generate unique ID for toggle
        $id = 'buildbyhs-spoiler-' . wp_unique_id();

        // Build output
        ob_start();
        ?>
        <div class="buildbyhs-spoiler">
            <button type="button" class="buildbyhs-spoiler-toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $id ); ?>">
                <?php echo esc_html( $title ); ?>
            </button>
            <div id="<?php echo esc_attr( $id ); ?>" class="buildbyhs-spoiler-content" hidden>
                <?php echo $content; ?>
            </div>
        </div>
        <script type="text/javascript">
        (function(){
            var btn = document.querySelector('.buildbyhs-spoiler-toggle[aria-controls="<?php echo esc_js( $id ); ?>"]');
            var content = document.getElementById('<?php echo esc_js( $id ); ?>');
            if ( btn && content ) {
                btn.addEventListener('click', function(){
                    var expanded = btn.getAttribute('aria-expanded') === 'true';
                    btn.setAttribute('aria-expanded', !expanded);
                    content.hidden = expanded;
                });
            }
        })();
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Registers the [spoiler] shortcode.
     */
    function buildbyhs_register_spoiler_shortcode() {
        add_shortcode( 'spoiler', 'buildbyhs_spoiler_shortcode' );
    }
    add_action( 'init', 'buildbyhs_register_spoiler_shortcode' );
}
