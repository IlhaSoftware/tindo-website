<?php

if ( ! function_exists('rock_popular_posts')) {
    /**
     * Count pageviews for a post
     *
     * @param $postID
     *
     * @see https://www.wpbeginner.com/wp-tutorials/how-to-track-popular-posts-by-views-in-wordpress-without-a-plugin/
     */
    function rock_set_page_view($postID)
    {
        $count_key = 'rock_post_views_count';
        $count     = get_post_meta($postID, $count_key, true);
        if ($count == '') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, 1);
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }

}

//To keep the count accurate, lets get rid of prefetching
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

if ( ! function_exists('rock_auto_track_post_views')) {
    /**
     * Automatic track pageviews for single posts/pages
     *
     * @param $post_id
     *
     * @see https://www.wpbeginner.com/wp-tutorials/how-to-track-popular-posts-by-views-in-wordpress-without-a-plugin/
     */
    function rock_auto_track_post_views($post_id)
    {
        if ( ! is_single()) {
            return;
        }
        if (empty ($post_id)) {
            global $post;
            $post_id = $post->ID;
        }

        rock_set_page_view($post_id);
    }

}

add_action('wp_head', 'rock_auto_track_post_views');


// Register and load the widget
function rock_load_popular_posts_widget()
{
    register_widget('rock_popular_posts_widget');
}

add_action('widgets_init', 'rock_load_popular_posts_widget');

// Creating the widget
class rock_popular_posts_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

        // Base ID of your widget
            'rock_popular_posts_widget',

            // Widget name will appear in UI
            __('Posts populares | Rock Content', 'rockcontent'),

            // Widget description
            array('description' => __('Lista dos posts populares do blog', 'rockcontent'),)
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $num_posts = $instance['num_posts'];
        $title     = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

        $popular_posts = new WP_Query(array(
            'posts_per_page' => $num_posts,
            'meta_key'       => 'rock_post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ));

        if ($popular_posts->have_posts()) {
            echo "<ul class='rock-popular-posts'>";
            while ($popular_posts->have_posts()) {
                $popular_posts->the_post();
                ?>
                <li class="rock-popular-posts__post">
                    <a href="<?php echo get_the_permalink(); ?>" class="rock-popular-posts__post__link">
                        <?php the_post_thumbnail('thumbnail', array('class' => 'rock-popular-posts__post__thumbnail')) ?>
                        <div><?php the_title(); ?></div>
                    </a>
                </li>
                <?php
            }
            wp_reset_postdata();
            echo "</ul>";
        }

        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Posts populares';
        }

        if (isset($instance['num_posts'])) {
            $num_posts = $instance['num_posts'];
        } else {
            $num_posts = 3;
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Titulo:',
                    'rockcontent'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php echo __('Número de posts:',
                    'rockcontent'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>"
                   name="<?php echo $this->get_field_name('num_posts'); ?>"
                   type="text" value="<?php echo esc_attr($num_posts); ?>"/>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance              = array();
        $instance['num_posts'] = ( ! empty($new_instance['num_posts'])) ? strip_tags(intval($new_instance['num_posts'])) : 3;
        $instance['title']     = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
}
