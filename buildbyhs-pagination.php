<?php
/**
 * File: buildbyhs-pagination.php
 * Function: buildbyhs_pagination
 * Description: Outputs numbered pagination links for archive or query pages,
 *              with proper sanitization and escaping.
 *
 * Usage:
 * // In your archive.php or custom loop template:
 * if ( function_exists( 'buildbyhs_pagination' ) ) {
 *     buildbyhs_pagination();
 * }
 */

if ( ! function_exists( 'buildbyhs_pagination' ) ) {
    /**
     * Display pagination links.
     *
     * @param array $args Optional args: 'mid_size', 'prev_text', 'next_text'.
     */
    function buildbyhs_pagination( $args = array() ) {
        global $wp_query;

        $defaults = array(
            'mid_size'  => 2,
            'prev_text' => __( '&laquo; Previous', 'buildbyhs' ),
            'next_text' => __( 'Next &raquo;', 'buildbyhs' ),
            'type'      => 'array',
        );
        $args = wp_parse_args( $args, $defaults );

        $total   = isset( $wp_query->max_num_pages ) ? intval( $wp_query->max_num_pages ) : 1;
        if ( $total < 2 ) {
            return;
        }

        $current = max( 1, get_query_var( 'paged' ) );

        $links = paginate_links( array(
            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'    => '?paged=%#%',
            'current'   => $current,
            'total'     => $total,
            'mid_size'  => intval( $args['mid_size'] ),
            'prev_text' => esc_html( $args['prev_text'] ),
            'next_text' => esc_html( $args['next_text'] ),
            'type'      => $args['type'],
        ) );

        if ( is_array( $links ) ) {
            echo '<nav class="buildbyhs-pagination" aria-label="Pagination"><ul class="pagination">';
            foreach ( $links as $link ) {
                // Wrap each link in <li>
                echo '<li class="page-item">' . $link . '</li>';
            }
            echo '</ul></nav>';
        }
    }
}
