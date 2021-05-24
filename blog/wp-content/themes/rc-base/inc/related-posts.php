<?php

if ( ! function_exists('rock_related_posts')) {
    /**
     * Get posts in same category of a post. The result is cached for 12 hours.
     *
     * @param $post_id
     * @param int $limit
     *
     * @return mixed|WP_Query
     *
     * @see https://codex.wordpress.org/Transients_API
     * @see https://developer.wordpress.org/reference/functions/wp_get_post_categories/
     */
    function rock_related_posts($post_id, $limit = 3)
    {
        if (false === ($related = get_transient('rock_related_posts'))) {

            $categories     = wp_get_post_categories($post_id);
            $categories_ids = array_values($categories);

            $related = new WP_Query(
                array(
                    'category__in'   => $categories_ids,
                    'post_type'      => 'post',
                    'posts_per_page' => $limit,
                    'order'          => 'DESC',
                    'orderby'        => 'date'
                )
            );

            // Put the results in a transient. Expire after 12 hours.
            set_transient('rock_related_posts', $related, 12 * HOUR_IN_SECONDS);
        }

        return $related;
    }
}


?>
