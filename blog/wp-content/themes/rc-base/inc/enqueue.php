<?php
/**
 * Rock Content enqueue scripts
 *
 * @package rockcontent
 */

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists('rock_scripts')) {
    /**
     * Load theme's JavaScript and CSS sources.
     */
    function rock_scripts()
    {
        // Get the theme data.
        $the_theme     = wp_get_theme();
        $theme_version = $the_theme->get('Version');

        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/theme.min.css');
        wp_enqueue_style('rock-content-styles', get_stylesheet_directory_uri() . '/css/theme.min.css', array(),
            $css_version);

        /**
         * Enqueue jQuery from CDN
         */
        wp_deregister_script('jquery');
        wp_register_script('jquery', "https://code.jquery.com/jquery-3.3.1.min.js", array(), null);
        wp_enqueue_script('jquery');

        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/js/theme.min.js');
        wp_enqueue_script('rock-content-scripts', get_template_directory_uri() . '/js/theme.min.js', array(),
            $js_version, true);
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

    }
} // endif function_exists( 'rock_scripts' ).

add_action('wp_enqueue_scripts', 'rock_scripts');


function rock_dequeue_scripts()
{
    /**
     * Dequeue Lazy load script. It is already included in theme.min.js
     */
    wp_deregister_script('BJLL');
    wp_dequeue_script('BJLL');

    /**
     * Remove embed script
     */
    wp_deregister_script('wp-embed');
}

add_action('wp_enqueue_scripts', 'rock_dequeue_scripts', 100);
