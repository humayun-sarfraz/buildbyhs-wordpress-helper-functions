<?php
/**
 * File: buildbyhs-wpml-language-switcher.php
 * Function: buildbyhs_wpml_language_switcher
 * Description: Outputs a WPML language switcher list or dropdown, properly escaped and translatable.
 *
 * Usage:
 * // In your theme or plugin:
 * require_once get_stylesheet_directory() . '/buildbyhs-wpml-language-switcher.php';
 * // To echo a dropdown:
 * buildbyhs_wpml_language_switcher( array( 'type' => 'dropdown' ) );
 * // To echo as list:
 * buildbyhs_wpml_language_switcher( array( 'type' => 'list', 'class' => 'lang-switcher' ) );
 */

if ( ! function_exists( 'buildbyhs_wpml_language_switcher' ) ) {
    /**
     * Display a WPML language switcher.
     *
     * @param array $args Optional args:
     *                    'type'      => 'list' or 'dropdown' (default 'list'),
     *                    'class'     => additional CSS class for wrapper,
     *                    'show_flags'=> bool to show flag images (list only, default true),
     *                    'show_names'=> bool to show language names (list or dropdown, default true).
     */
    function buildbyhs_wpml_language_switcher( $args = array() ) {
        if ( ! function_exists( 'icl_get_languages' ) ) {
            return;
        }
        $defaults = array(
            'type'       => 'list',
            'class'      => '',
            'show_flags' => true,
            'show_names' => true,
        );
        $r = wp_parse_args( $args, $defaults );
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if ( empty( $languages ) ) {
            return;
        }

        $type       = sanitize_text_field( $r['type'] );
        $wrapper_class = sanitize_html_class( $r['class'] );
        $show_flags = boolval( $r['show_flags'] );
        $show_names = boolval( $r['show_names'] );

        if ( 'dropdown' === $type ) {
            echo '<select class="buildbyhs-language-switcher ' . esc_attr( $wrapper_class ) . '">';
            foreach ( $languages as $lang ) {
                $selected = $lang['active'] ? ' selected' : '';
                $url = esc_url( $lang['url'] );
                $name = esc_html( $lang['translated_name'] );
                echo '<option value="' . $url . '"' . $selected . '>' . ( $show_names ? $name : '' ) . '</option>';
            }
            echo '</select>';
            ?>
            <script type="text/javascript">
            (function(){
                var sel = document.querySelector('.buildbyhs-language-switcher');
                if ( sel ) {
                    sel.addEventListener('change', function(){ window.location.href = this.value; });
                }
            })();
            </script>
            <?php
        } else {
            // list
            echo '<ul class="buildbyhs-language-switcher ' . esc_attr( $wrapper_class ) . '">';
            foreach ( $languages as $lang ) {
                $active = $lang['active'] ? ' active' : '';
                echo '<li class="lang-item' . $active . '">';
                echo '<a href="' . esc_url( $lang['url'] ) . '" lang="' . esc_attr( $lang['language_code'] ) . '">';
                if ( $show_flags && ! empty( $lang['country_flag_url'] ) ) {
                    echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['translated_name'] ) . '" class="lang-flag" /> ';
                }
                if ( $show_names ) {
                    echo esc_html( $lang['translated_name'] );
                }
                echo '</a></li>';
            }
            echo '</ul>';
        }
    }
}
