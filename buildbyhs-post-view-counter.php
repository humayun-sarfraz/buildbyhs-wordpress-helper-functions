<?php
/**
 * File: buildbyhs-post-view-counter.php
 * Function: buildbyhs_count_post_views, buildbyhs_get_post_views
 * Description: Tracks and stores how many times a post has been viewed (in post meta),
 *              and provides a helper to retrieve the count.
 *
 * Usage:
 * // 1) In your theme or plugin, hook the counter to runs on single post views:
 * add_action( 'wp', 'buildbyhs_count_post_views' );
 *
 * // 2) To display the count in a template:
 * printf(
 *     '<span class="post-views">%s views</span>',
 *     esc_html( buildbyhs_get_post_views( get_the_ID() ) )
 * );
 */

if ( ! function_exists( 'buildbyhs_count_post_views' ) ) {
    function buildbyhs_count_post_views() {
        if ( ! is_singular( 'post' ) ) {
            return;
        }

        global $post;
        $post_id = intval( $post->ID );

        // Get current view count (default 0)
        $count = intval( get_post_meta( $post_id, 'buildbyhs_post_views', true ) );
        $count++;

        // Update the meta value
        update_post_meta( $post_id, 'buildbyhs_post_views', $count );
    }
}

if ( ! function_exists( 'buildbyhs_get_post_views' ) ) {
    /**
     * Retrieve the view count for a given post.
     *
     * @param int $post_id Post ID.
     * @return int Number of views.
     */
    function buildbyhs_get_post_views( $post_id ) {
        $post_id = intval( $post_id );
        $count   = get_post_meta( $post_id, 'buildbyhs_post_views', true );

        return $count ? intval( $count ) : 0;
    }
}
